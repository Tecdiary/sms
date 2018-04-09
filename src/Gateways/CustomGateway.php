<?php

namespace Tecdiary\Sms\Gateways;

class CustomGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $url = '';
    protected $params = [];

    public function __construct($config)
    {
        $this->config = $config;
        $this->url = $this->config[$this->config['gateway']]['url'];
        $this->params = $this->config[$this->config['gateway']]['params']['others'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params[$this->config[$this->config['gateway']]['params']['send_to_name']] = $mobile;
        $this->params[$this->config[$this->config['gateway']]['params']['msg_name']] = $message;
        $client = new \GuzzleHttp\Client();
        try {
            $this->response = $client->get($this->getUrl())->getBody()->getContents();
        } catch (\Exception $e) {
            $this->response = ['error' => $e->getMessage()];
        }
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
