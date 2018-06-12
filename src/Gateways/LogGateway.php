<?php

namespace Tecdiary\Sms\Gateways;

class LogGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getUrl()
    {
        return true;
    }

    public function sendSms($mobile, $message)
    {
        $this->response = ['mobile' => $mobile, 'message' => $message];
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
