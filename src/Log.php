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

    public function log($message, $level = 'debug', $extra = '')
    {
        return $this->logger->$level($message, $extra);
    }

    public function info($message, $extra = '')
    {
        return $this->log($message, 'info', $extra);
    }

    public function error($message, $extra = '')
    {
        return $this->log($message, 'error', $extra);
    }
}
