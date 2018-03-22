<?php

namespace Tecdiary\Sms;

class Sms
{
    public $logger;
    public $gateway;
    public $status = false;

    public function __construct($config)
    {
        $gateway = '\\Tecdiary\\Sms\\Gateways\\' . $config['gateway'] . 'Gateway';
        $this->logger = new \Tecdiary\Sms\Log($config['log']);
        $this->gateway = new $gateway($config, $this->logger);
    }

    public function send($phone_numbers, $message)
    {
        if ($phone_numbers = $this->composeBulkNumbers($phone_numbers)) {
            return $this->gateway->sendSms($phone_numbers, $message);
        }
        return $this;
    }

    public function composeBulkNumbers($phone_numbers)
    {
        if (!is_array($phone_numbers)) {
            $phone_numbers = explode(',', $phone_numbers);
        }
        $new_phone_numbers = [];
        foreach ($phone_numbers as $number) {
            $number = (int) $number;
            $number = '+'.$number;
            try {
                if (\Brick\PhoneNumber\PhoneNumber::parse($number)->isValidNumber()) {
                    $new_phone_numbers[] = $number;
                } else {
                    $this->logger->error('Invalid Number, skipped from list', ['phone' => $number]);
                }
            } catch (\Brick\PhoneNumber\PhoneNumberParseException $e) {
                $this->logger->error($e->getMessage(), ['phone' => $number]);
            }
        }
        $numbers = implode(',', $new_phone_numbers);
        return $numbers;
    }

    public function response()
    {
        return $this->status;
    }
}
