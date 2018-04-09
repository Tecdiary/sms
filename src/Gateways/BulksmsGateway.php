<?php

namespace Tecdiary\Sms\Gateways;

class BulksmsGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = '';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['msisdn'] = '';
        $this->params['message'] = '';
        $this->params['username'] = $this->config[$this->config['gateway']]['username'];
        $this->params['password'] = $this->config[$this->config['gateway']]['password'];
        $this->url = $this->config[$this->config['gateway']]['eapi_url']."/submission/send_sms/2/2.0?";
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['msisdn'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
        try {
            $response = $client->get($this->getUrl())->getBody()->getContents();
            $this->response = explode('|', $response);
            if ($this->response[0] > 1) {
                throw new \Exception($response);
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
