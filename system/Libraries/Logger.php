<?php

namespace System\Libraries;

use App\Configs\Constants;

class Logger {
    // The instance of the Logger (Singleton pattern)
    private static $instance = null;

    private $logLevel;
    private $logFile;
    private $consoleOutput;

    const LOG_LEVELS = ['DISABLED', 'ERROR', 'DATA', 'WARNING', 'QUERY', 'DEBUG', 'INFO', 'ALL'];

    public function __construct(bool $consoleOutput = false) {
        $this->logLevel = Constants::LOGS_LEVEL;
        $this->consoleOutput = $consoleOutput;
        $this->logFile = Constants::LOGS_DIR_PATH . date('Y-m-d') . '.log';

        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    // Prevent object cloning
    private function __clone() {
    }

    // Get the singleton instance of the Logger
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
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

    public function data(string $message) {
        $this->log('DATA', $message);
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
