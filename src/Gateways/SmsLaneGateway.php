<?php

namespace Tecdiary\Sms\Gateways;

class SmsLaneGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'http://smslane.com/vendorsms/pushsms.aspx?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
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
        foreach ($this->params as $key => $val) {
            $this->request.= $key."=".urlencode($val);
            $this->request.= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['msisdn'] = $mobile;
        $this->params['msg'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('SmsLane Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
