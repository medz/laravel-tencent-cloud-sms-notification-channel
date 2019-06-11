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
                'nContent-Type: application/json',
                'Content-length: '.strlen($content),
            ]),
            'content' => $content,
        ]]);

        return file_get_contents($path, false, $context);
    }

    protected function make(TelContainer $to, Messages\TextMessage $message)
    {
        $rand = rand(100000000, 999999999);
        $time = time();
        $mobile = implode(',', $to->toArray());
        $sig = hash('sha256', http_build_query($data = [
            'appkey' => $this->appkey,
            'random' => rand(100000000, 999999999),
            'time' => time(),
            'mobile' => implode(',', $to->toArray()),
        ]));
        $message->setSig($sig);

        return ($to->hasMulti()? 'v5/tlssmssvr/sendmultisms2' : 'v5/tlssmssvr/sendsms')."?sdkappid={$this->appid}&random={$data['random']}";
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

        $this->http($this->make($to, $message), $message);
    }
}
