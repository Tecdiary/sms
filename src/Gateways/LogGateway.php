<?php

namespace Tecdiary\Sms\Gateways;

class LogGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';
    public $status = false;

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function getUrl()
    {
        return true;
    }

    public function sendSms($mobile, $message)
    {
        $gwvars['send_to'] = $mobile;
        $gwvars['msg'] = $message;
        $this->logger->info('SMS Saved to Log: ', $gwvars);
        $this->status = true;
        $this->response = 'Saved to Log File.';
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
