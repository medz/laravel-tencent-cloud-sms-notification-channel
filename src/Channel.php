<?php

namespace Medz\Laravel\Notifications\TencentCloudSMS;

use Illuminate\Notifications\Notification;

class Channel
{
    protected $appid;
    protected $appkey;

    public function __construct(string $appid, string $appkey)
    {
        $this->appid = $appid;
        $this->appkey = $appkey;
    }

    protected function http(string $path, Messages\TextMessage $message)
    {
        $content = (string) $message;
        $context = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => implode("\r\n", [
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-length: '.strlen($content),
                'User-Agent: Laravel Tencent Cloud SMS Channel, https://github.com/medz/laravel-tencent-cloud-sms-notification-channel',
            ]),
            'content' => $content,
        ]]);

        return file_get_contents('https://yun.tim.qq.com'.$path, false, $context);
    }

    protected function make(TelContainer $to, Messages\TextMessage $message)
    {
        if ($to->hasMulti()) {
            $mobile = implode(',', array_map(function (array $item) {
                return $item['mobile'];
            }, $to->toArray()));
        } else {
            $mobile = $to->toArray()['mobile'];
        }
        
        $dataStr = http_build_query($data = [
            'appkey' => $this->appkey,
            'random' => rand(100000000, 999999999),
            'time' => time(),
        ]);
        $dataStr .= '&mobile='.$mobile;
        $sig = hash('sha256', $dataStr);
        $message->setSig($sig);

        return ($to->hasMulti()? '/v5/tlssmssvr/sendmultisms2' : '/v5/tlssmssvr/sendsms')."?sdkappid={$this->appid}&random={$data['random']}";
    }

    public function send($notifiable, Notification $notification)
    {
        if (! ($to = $notifiable->routeNotificationFor('qcloud', $notification)) instanceof TelContainer) {
            return;
        }

        $message = $notification->toQcloud($notifiable);
        if (! $message instanceof Messages\TextMessage) {
            return;
        }
        $message->setTel($to);

        return json_decode(
            $this->http($this->make($to, $message), $message)
        );
    }
}
