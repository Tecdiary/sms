<?php

namespace Tecdiary\Sms\Gateways;

class MockerGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'http://mocker.in/sms/mocker?';


    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['to'] = '';
        $this->params['message'] = '';
        $this->params['sender_id'] = $this->config[$this->config['gateway']]['sender_id'];
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
        $this->params['to'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('Mocker Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return json_decode($this->response);
    }
}
