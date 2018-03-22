<?php

namespace Tecdiary\Sms\Gateways;

class InfobipGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'http://api.infobip.com/sms/1/text/query?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['to'] = '';
        $this->params['text'] = '';
        $this->params['username'] = $this->config[$this->config['gateway']]['username'];
        $this->params['password'] = $this->config[$this->config['gateway']]['password'];
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
        $this->params['to'] = $mobile;
        $this->params['text'] = $message;
        $client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('Infobip Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
