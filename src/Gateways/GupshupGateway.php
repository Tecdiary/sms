<?php

namespace Tecdiary\Sms\Gateways;

class GupshupGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'http://enterprise.smsgupshup.com/GatewayAPI/rest?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['send_to'] = '';
        $this->params['msg'] = '';
        $this->params['method'] = 'sendMessage';
        $this->params['userid'] = $this->config[$this->config['gateway']]['userid'];
        $this->params['password'] = $this->config[$this->config['gateway']]['password'];
        $this->params['v'] = "1.1";
        $this->params['msg_type'] = "TEXT";
        $this->params['auth_scheme'] = "PLAIN";
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['send_to'] = $mobile;
        $this->params['msg'] = $message;
        $client = new \GuzzleHttp\Client();
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
        $success = substr_count($this->response, 'success');
        $error = substr_count($this->response, 'error');
        return [
            'error' => $error,
            'status' => [
                'success' => $success,
                'error' => $error
            ],
            'response' => $this->response
        ];
    }
}
