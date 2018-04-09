<?php

namespace Tecdiary\Sms\Gateways;

class MockerGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'http://mocker.in/sms/mocker?';


    public function __construct($config)
    {
        $this->config = $config;
        $this->params['to'] = '';
        $this->params['message'] = '';
        $this->params['sender_id'] = $this->config[$this->config['gateway']]['sender_id'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['to'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get($this->getUrl())->getBody()->getContents();
            $this->response = json_decode($response, true);
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
