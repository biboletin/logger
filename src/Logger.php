<?php

namespace Bibo\Logger;

use Bibo\Logger\Formatter\FormatterInterface;
use Bibo\Logger\Handler\RotatingFileHandler;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;

class Logger implements LoggerInterface
{
    protected string $logFile;

    protected string $minLevel;

    protected array $logLevels = [
        LogLevel::EMERGENCY => 600,
        LogLevel::ALERT => 550,
        LogLevel::CRITICAL => 500,
        LogLevel::ERROR => 400,
        LogLevel::WARNING => 300,
        LogLevel::NOTICE => 250,
        LogLevel::INFO => 200,
        LogLevel::DEBUG => 100,
    ];

    protected FormatterInterface $formatter;

    protected RotatingFileHandler $handler;

    protected bool $useJsonFormat = false;

    public function __construct(FormatterInterface $formatter, RotatingFileHandler $handler = null)
    {
        $this->formatter = $formatter;
        $this->handler = $handler;
    }

    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $interpolated = $this->interpolate($message, $context);
        $formattedMessage = $this->formatter->format($level, $interpolated, $context);
    
        $this->handler->write($formattedMessage);
    }

    private function interpolate(string|Stringable $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $value) {
            $replace['{{' . $key . '}}'] = $value;
        }

        return strtr($message, $replace);
    }
}
