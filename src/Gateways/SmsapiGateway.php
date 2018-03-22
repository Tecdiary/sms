<?php

namespace Tecdiary\Sms\Gateways;

class SmsapiGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'https://api.smsapi.com/sms.do?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
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
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('Smsapi Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
