<?php

namespace Tecdiary\Sms;

class Sms
{
    public $config;
    public $logger;
    public $gateway;
    public $status = false;

    public function __construct($config)
    {
        $this->config = $config;
        $this->logger = new \Tecdiary\Sms\Log($config['log']);
        $gateway = '\\Tecdiary\\Sms\\Gateways\\' . $config['gateway'] . 'Gateway';
        $this->gateway = new $gateway($config);
    }

    public function send($phone_numbers, $message)
    {
        if ($phone_numbers = $this->composeBulkNumbers($phone_numbers)) {
            try {
                if ($result = $this->gateway->sendSms($phone_numbers, $message)) {
                    $response = $result->response();
                    $level = isset($response['error']) && $response['error'] ? 'error' : 'info';
                    $this->logger->$level($this->config['gateway'] . ' response', $response);
                } else {
                    throw new \Exception('Invalid Number ' . $number);
                }
            } catch (\Exception $e) {
                $result = false;
                $response = ['error' => $e->getMessage()];
                $this->logger->error($this->config['gateway'] . ' response', $response);
            }
        }
        return isset($result) ? $result : $this;
    }

    public function composeBulkNumbers($phone_numbers)
    {
        if (!is_array($phone_numbers)) {
            $phone_numbers = explode(',', $phone_numbers);
        }
        $new_phone_numbers = [];
        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        foreach ($phone_numbers as $number) {
            $phoneNumber = $phoneNumberUtil->parse($number, null, null, true);
            try {
                $isPossibleNumberWithReason = $phoneNumberUtil->isPossibleNumberWithReason($phoneNumber);
                if ($isPossibleNumberWithReason == 0) {
                    $new_phone_numbers[] = $number;
                } else {
                    if ($isPossibleNumberWithReason == 1) {
                        $this->logger->error('Invalid Number, skipped from list', ['phone' => $number, 'reason' => 'INVALID_COUNTRY_CODE']);
                    } elseif ($isPossibleNumberWithReason == 2) {
                        $this->logger->error('Invalid Number, skipped from list', ['phone' => $number, 'reason' => 'TOO_SHORT']);
                    } elseif ($isPossibleNumberWithReason == 3) {
                        $this->logger->error('Invalid Number, skipped from list', ['phone' => $number, 'reason' => 'TOO_LONG']);
                    } else {
                        $this->logger->error('Invalid Number, skipped from list', ['phone' => $number, 'reason' => 'UNKNOWN']);
                    }
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['phone' => $number]);
                throw new \Exception($e->getMessage() . ' ' . $number);
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
