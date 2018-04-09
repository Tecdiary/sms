<?php

namespace Tecdiary\Sms\Gateways;

class LogGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';
    public $status = false;

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
        $params['send_to'] = $mobile;
        $params['msg'] = $message;
        $this->status = true;
        $this->response = 'Saved to Log File.';
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
