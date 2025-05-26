<?php

namespace Bibo\Logger\Formatter;

class LineFormatter implements FormatterInterface
{
    protected string $dateFormat;
    protected bool $includeContext;

    public function __construct(string $dateFormat = 'Y-m-d H:i:s', bool $includeContext = true)
    {
        $this->dateFormat = $dateFormat;
        $this->includeContext = $includeContext;
    }

    public function format(string $level, string|\Stringable $message, array $context = []): string
    {
        $timestamp = date($this->dateFormat);
        $level = strtoupper($level);
        $output = sprintf("[%s] %s: %s", $timestamp, $level, (string) $message);

        if ($this->includeContext && !empty($context)) {
            $output .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return $output;
    }
}
