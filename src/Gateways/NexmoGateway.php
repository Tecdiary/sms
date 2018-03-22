<?php

namespace Tecdiary\Sms\Gateways;

class NexmoGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'https://rest.nexmo.com/sms/json';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['to'] = '';
        $this->params['text'] = '';
        $this->params['api_key'] = $this->config[$this->config['gateway']]['api_key'];
        $this->params['api_secret'] = $this->config[$this->config['gateway']]['api_secret'];
        $this->params['from'] = $this->config[$this->config['gateway']]['from'];
    }

    public function getUrl()
    {
        foreach ($this->params as $key => $val) {
            $this->request .= $key."=".urlencode($val);
            $this->request .= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url;
    }

    public function sendSms($mobile, $message)
    {
        $mobiles = explode(',', $mobile);
        $this->composeBulkMobile($mobiles, $message);
        return $this;
    }

    private function composeSingleMobile($mobile, $message)
    {
        $this->params['to'] = $mobile;
        $this->params['text'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(), ['form_params'=>$this->params])->getBody()->getContents();
        $this->logger->info('Nexmo Response: '.$this->response);
        return $this->response;
    }

    private function composeBulkMobile($mobiles, $message)
    {
        foreach ($mobiles as $mobile) {
            $this->composeSingleMobile($mobile, $message);
        }
    }

    public function response()
    {
        $response = json_decode($this->response);
        return $response;
    }
}
