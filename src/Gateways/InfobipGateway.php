<?php

namespace Tecdiary\Sms\Gateways;

class InfobipGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'http://api.infobip.com/sms/1/text/query?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['to'] = '';
        $this->params['text'] = '';
        $this->params['username'] = $this->config[$this->config['gateway']]['username'];
        $this->params['password'] = $this->config[$this->config['gateway']]['password'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['to'] = $mobile;
        $this->params['text'] = $message;
        $client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
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
