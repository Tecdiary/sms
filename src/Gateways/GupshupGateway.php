<?php

namespace Tecdiary\Sms\Gateways;

class GupshupGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'http://enterprise.smsgupshup.com/GatewayAPI/rest?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
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
        foreach ($this->params as $key => $val) {
            $this->request.= $key."=".urlencode($val);
            $this->request.= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['send_to'] = $mobile;
        $this->params['msg'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('Gupshup Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        $success = substr_count($this->response, 'success');
        $error = substr_count($this->response, 'error');
        return [
            'status' => [
                'success' => $success,
                'error' => $error
            ],
            'response' => $this->response
        ];
    }
}
