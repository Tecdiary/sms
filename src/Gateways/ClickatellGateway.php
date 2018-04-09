<?php

namespace Tecdiary\Sms\Gateways;

class ClickatellGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'https://platform.clickatell.com/messages/http/send?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['to'] = '';
        $this->params['content'] = '';
        $this->params['apiKey'] = $this->config[$this->config['gateway']]['apiKey'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['to'] = $mobile;
        $this->params['content'] = $message;
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get($this->getUrl())->getBody()->getContents();
            $this->response = json_decode($response, true);
            if ($this->response['errorCode']) {
                throw new \Exception($this->response['errorCode'].': '.$this->response['error']);
            }
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
