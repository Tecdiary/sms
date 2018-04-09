<?php

namespace Tecdiary\Sms\Gateways;

class ItexmoGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'https://www.itexmo.com/php_api/api.php';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['3'] = $this->config[$this->config['gateway']]['api_code'];
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['1'] = $mobile;
        $this->params['2'] = $message;
        $client = new \GuzzleHttp\Client();
        try {
            $this->response = $client->post($this->getUrl(), ['form_params'=>$this->params])->getBody()->getContents();
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
