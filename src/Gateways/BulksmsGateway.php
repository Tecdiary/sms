<?php

namespace Tecdiary\Sms\Gateways;

class BulksmsGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = '';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['msisdn'] = '';
        $this->params['message'] = '';
        $this->params['username'] = $this->config[$this->config['gateway']]['username'];
        $this->params['username'] = $this->config[$this->config['gateway']]['username'];
        $this->url = $this->config[$this->config['gateway']]['eapi_url']."/submission/send_sms/2/2.0?";
    }

    public function getUrl()
    {
        foreach ($this->params as $key => $val) {
            $this->request .= $key . "=" . urlencode($val);
            $this->request .= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['msisdn'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('Bulksms Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
