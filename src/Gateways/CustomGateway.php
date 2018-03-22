<?php

namespace Tecdiary\Sms\Gateways;

class CustomGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $url = '';
    protected $params = [];
    protected $request = '';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->url = $this->config[$this->config['gateway']]['url'];
        $this->params = $this->config[$this->config['gateway']]['params']['others'];
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
        $this->params[$this->config[$this->config['gateway']]['params']['send_to_name']] = $mobile;
        $this->params[$this->config[$this->config['gateway']]['params']['msg_name']] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('Custom SMS Gateway Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
