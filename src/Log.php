<?php

namespace Tecdiary\Sms;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class Log
{

    public function __construct($log)
    {
        $this->logger = new Logger('SMS');
        $this->logger->pushHandler(new RotatingFileHandler($log['path'], 90, $log['level']));
    }

    public function log($message, $level = 'debug', $extra = [])
    {
        return $this->logger->$level($message, $this->checkExtra($extra));
    }

    public function info($message, $extra = [])
    {
        return $this->log($message, 'info', $this->checkExtra($extra));
    }

    public function error($message, $extra = [])
    {
        return $this->log($message, 'error', $this->checkExtra($extra));
    }

    private function checkExtra($extra)
    {
        $ext = false;
        if (!is_array($extra)) {
            $ext[] = $extra;
        }
        return $ext ? $ext : $extra;
    }
}
