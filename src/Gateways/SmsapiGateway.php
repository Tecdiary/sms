<?php

namespace Tecdiary\Sms\Gateways;

class SmsapiGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'https://api.smsapi.com/sms.do?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['to'] = '';
        $this->params['message'] = '';
        $this->params['from'] = $this->config[$this->config['gateway']]['from'];
        $this->params['access_token'] = $this->config[$this->config['gateway']]['access_token'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['to'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
        try {
            $this->response = $client->get($this->getUrl())->getBody()->getContents();
            if (empty($this->response) || $this->response['error']) {
                throw new \Exception($this->response['error'].': '.$this->response['message']);
            }
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
