<?php

namespace Medz\Laravel\Notifications\TencentCloudSMS;

use Illuminate\Support\Facades\Notification;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        Notification::extend('qcloud', function ($app) {
            return new Channel($app['config']['services.qcloud_sms.appid'], $app['config']['services.qcloud_sms.appkey']);
        });
    }
}
