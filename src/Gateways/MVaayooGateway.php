<?php

namespace Tecdiary\Sms\Gateways;

class MVaayooGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $gwvars = [];
    protected $request = '';
    protected $status = false;
    protected $url = 'http://api.mVaayoo.com/mvaayooapi/MessageCompose?';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->gwvars['receipientno'] = '';
        $this->gwvars['msgtxt'] = '';
        $this->gwvars['senderID'] = $this->config[$this->config['gateway']]['senderID'];
        $this->gwvars['user'] = $this->config[$this->config['gateway']]['user'];
        $this->gwvars['msgtype'] = 0;
        $this->gwvars['state'] = 4;
    }

    public function getUrl()
    {
        foreach ($this->gwvars as $key => $val) {
            $this->request.= $key."=".urlencode($val);
            $this->request.= "&";
        }
        $this->request = substr($this->request, 0, strlen($this->request)-1);
        return $this->url.$this->request;
    }

    public function sendSms($mobile, $message)
    {
        $this->gwvars['receipientno'] = $mobile;
        $this->gwvars['msgtxt'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get($this->getUrl())->getBody()->getContents();
        $this->logger->info('MVaayoo Response: '.$this->response);
        return $this;
    }

    public function response()
    {
        $status = explode(',', $this->response);
        if (trim($status[0]) == 'Status=0') {
            $this->status = true;
        }
        return ['status' => $this->status, 'response' => $this->response];
    }
}
