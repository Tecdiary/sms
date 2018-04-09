<?php

namespace Tecdiary\Sms\Gateways;

class SmsCountryGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'http://api.smscountry.com/SMSCwebservice_bulk.aspx?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['mobilenumber'] = '';
        $this->params['message'] = '';
        $this->params['User'] = $this->config[$this->config['gateway']]['user'];
        $this->params['passwd'] = $this->config[$this->config['gateway']]['passwd'];
        $this->params['sid'] = "";
        $this->params['mtype'] = "N";
        $this->params['DR'] = "Y";
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
    }

    public function sendSms($mobile, $message)
    {
        $this->params['mobilenumber'] = $mobile;
        $this->params['message'] = $message;
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
