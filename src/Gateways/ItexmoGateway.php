<?php

namespace Tecdiary\Sms\Gateways;

class ItexmoGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'https://www.itexmo.com/php_api/api.php';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['3'] = $this->config[$this->config['gateway']]['api_code'];
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['1'] = $mobile;
        $this->params['2'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(), ['form_params'=>$this->params])->getBody()->getContents();
        $this->logger->info('Itexmo Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
