<?php

namespace Tecdiary\Sms\Gateways;

class MVaayooGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'http://api.mVaayoo.com/mvaayooapi/MessageCompose?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['receipientno'] = '';
        $this->params['msgtxt'] = '';
        $this->params['senderID'] = $this->config[$this->config['gateway']]['senderID'];
        $this->params['user'] = $this->config[$this->config['gateway']]['user'];
        $this->params['msgtype'] = 0;
        $this->params['state'] = 4;
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['receipientno'] = $mobile;
        $this->params['msgtxt'] = $message;
        $client = new \GuzzleHttp\Client();
        try {
            $this->response = $client->get($this->getUrl())->getBody()->getContents();
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
