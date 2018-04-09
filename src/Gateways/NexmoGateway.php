<?php

namespace Tecdiary\Sms\Gateways;

class NexmoGateway implements SmsGatewayInterface
{
    public $config;
    public $response = '';

    protected $params = [];
    protected $url = 'https://rest.nexmo.com/sms/json?';

    public function __construct($config)
    {
        $this->config = $config;
        $this->params['to'] = '';
        $this->params['text'] = '';
        $this->params['api_key'] = $this->config[$this->config['gateway']]['api_key'];
        $this->params['api_secret'] = $this->config[$this->config['gateway']]['api_secret'];
        $this->params['from'] = $this->config[$this->config['gateway']]['from'];
    }

    public function getUrl()
    {
        return $this->url.http_build_query($this->params);
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
        try {
            $response = $client->post($this->getUrl(), ['form_params'=>$this->params])->getBody()->getContents();
            $this->response = json_decode($response, true);
            foreach ($this->response['messages'] as $message) {
                if ($message['status'] != 0) {
                    throw new \Exception('To: ' .$message['to'].', Error Code: '.$message['status'].', Error Text: '.$message['error-text']);
                }
            }
        } catch (\Exception $e) {
            $this->response = ['error' => $e->getMessage()];
        }
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
        return $this->response;
    }
}
