<?php

namespace Tecdiary\Sms\Gateways;

class SmsLaneGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'http://smslane.com/vendorsms/pushsms.aspx?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['msisdn'] = '';
        $this->params['msg'] = '';
        $this->params['user'] = $this->config[$this->config['gateway']]['user'];
        $this->params['password'] = $this->config[$this->config['gateway']]['password'];
        $this->params['sid'] = $this->config[$this->config['gateway']]['sid'];
        $this->params['fl'] = "0";
        $this->params['gwid'] = $this->config[$this->config['gateway']]['gwid'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['msisdn'] = $mobile;
        $this->params['msg'] = $message;
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
