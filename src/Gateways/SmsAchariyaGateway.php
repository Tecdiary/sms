<?php

namespace Tecdiary\Sms\Gateways;

class SmsAchariyaGateway implements SmsGatewayInterface
{
    public $config;
    public $logger;
    public $response = '';

    protected $url = '';
    protected $params = [];
    protected $request = '';

    public function __construct($config, $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->params['uid'] = $this->config[$this->config['gateway']]['uid'];
        $this->params['pin'] = $this->config[$this->config['gateway']]['pin'];
        $this->params['sender'] = '';
        $this->params['route'] = '0';
        $this->params['mobile'] = '';
        $this->params['message'] = '';
        $this->params['push_id'] = 1;
        $this->url = "http://".$this->config[$this->config['gateway']]['domain']."/api/sms.php?";
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
        $this->params['mobile'] = $mobile;
        $this->params['message'] = $message;
        $client = new \GuzzleHttp\Client();
        $this->response = $client->post($this->getUrl(), ['form_params' => $this->params])->getBody()->getContents();
        $this->logger->info('SMS Achariya Response: '.$this->response);
        return $this;
    }

    public function route($route = 0)
    {
        $this->params['route'] = $route;
        return $this;
    }

    public function sender($sender)
    {
        $this->params['sender'] = $sender;
        return $this;
    }

    public function response()
    {
        $client = new \GuzzleHttp\Client();
        $report = $client->post("http://".$this->config[$this->config['gateway']]['domain']."/api/dlr.php?", [
            'form_params' => [
                'uid' => $this->params['uid'],
                'pin' => $this->params['pin'],
                'msgid' => $this->response
            ]
        ])->getBody()->getContents();
        $report = trim($report, ',');
        $this->logger->info('SMS Achariya Delivery Report: '.$report);
        $exrepos = explode(',', $report);
        $sent = 0;
        $delivered = 0;
        $dnd = 0;
        $error = 0;
        foreach ($exrepos as $exrepo) {
            if ($exrepo == 'Sent') {
                $sent++;
            } elseif ($exrepo == 'Delivered') {
                $delivered++;
            } elseif ($exrepo == 'DND') {
                $dnd++;
            } else {
                $error++;
            }
        }

        return [
            'status' => [
                'sent' => $sent,
                'delivered' => $delivered,
                'dnd' => $dnd,
                'error' => $error
            ],
            'response' => $this->response,
            'report' => $report,
            'mobile' => $this->params['mobile']
        ];
    }
}
