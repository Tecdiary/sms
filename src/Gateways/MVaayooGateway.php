<?php

namespace Tecdiary\Sms\Gateways;

class MVaayooGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $params = [];
    protected $request = '';
    protected $url = 'http://api.mVaayoo.com/mvaayooapi/MessageCompose?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['receipientno'] = '';
        $this->params['msgtxt'] = '';
        $this->params['senderID'] = $this->config[$this->config['gateway']]['senderID'];
        $this->params['user'] = $this->config[$this->config['gateway']]['user'];
        $this->params['msgtype'] = 0;
        $this->params['state'] = 4;
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
        $this->params['receipientno'] = $mobile;
        $this->params['msgtxt'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('MVaayoo Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}
