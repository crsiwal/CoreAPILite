<?php

namespace System\Libraries;

use App\Configs\Constants;

class Logger {
    private $logLevel;
    private $logFile;
    private $consoleOutput;

    const LOG_LEVELS = [
        0 => 'DISABLED',
        1 => 'ERROR',
        2 => 'WARNING',
        3 => 'QUERY',
        4 => 'DEBUG',
        5 => 'INFO',
        6 => 'ALL'
    ];

    public function __construct(bool $consoleOutput = false) {
        $this->logLevel = Constants::LOGS_LEVEL;
        $this->consoleOutput = $consoleOutput;
        $this->logFile = Constants::LOGS_DIR_PATH . date('Y-m-d') . '.log';

        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    private function log(string $level, string $message) {
        if ($this->logLevel === 0 || array_search($level, self::LOG_LEVELS) > $this->logLevel) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "$level - $timestamp --> $message" . PHP_EOL;

        file_put_contents($this->logFile, $logEntry, FILE_APPEND);

        if ($this->consoleOutput) {
            echo $logEntry;
        }
    }

    public function error(string $message) {
        $this->log('ERROR', $message);
    }

    public function warning(string $message) {
        $this->log('WARNING', $message);
    }

    public function query(string $message) {
        $this->log('QUERY', $message);
    }

    public function debug(string $message) {
        $this->log('DEBUG', $message);
    }

    public function info(string $message) {
        $this->log('INFO', $message);
    }

    public function setLogLevel(int $logLevel) {
        $this->logLevel = $logLevel;
    }

    public function setConsoleOutput(bool $consoleOutput) {
        $this->consoleOutput = $consoleOutput;
    }
}
