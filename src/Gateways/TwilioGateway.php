<?php

namespace Tecdiary\Sms\Gateways;

class TwilioGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $url = '';
    protected $params = [];
    protected $request = '';
    protected $credentials = [];

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['To'] = '';
        $this->params['Body'] = '';
        $this->params['From'] = $this->config[$this->config['gateway']]['from'];
        $this->credentials['sid'] = $this->config[$this->config['gateway']]['account_sid'];
        $this->credentials['token'] = $this->config[$this->config['gateway']]['auth_token'];
        $this->credentials['authorization'] = base64_encode($this->credentials['sid'].':'.$this->credentials['token']);
        $this->url = "https://api.twilio.com/2010-04-01/Accounts/".$this->credentials['sid']."/Messages.json";
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['To'] = $mobile;
        $this->params['Body'] = $message;
        $client = new \GuzzleHttp\Client(['headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->credentials['authorization']
        ]]);
        try {
            $this->response = $client->request('POST', $this->getUrl(), $this->params)->getBody()->getContents();
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
