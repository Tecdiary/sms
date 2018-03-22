<?php

namespace Tecdiary\Sms\Gateways;

class SmsCountryGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'http://api.smscountry.com/SMSCwebservice_bulk.aspx?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
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
        foreach ($this->params as $key => $val) {
            $this->request .= $key."=".urlencode($val);
            $this->request .= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;
    }

    public function sendSms($mobile, $message)
    {
        $this->params['mobilenumber'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('SmsCountry Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
