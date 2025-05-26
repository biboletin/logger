<?php

namespace Bibo\Logger\Formatter;

use DateTime;

class CSVFormatter implements FormatterInterface
{
    protected string $dateFormat;
    protected string $delimiter;
    protected string $enclosure;

    public function __construct(
        string $dateFormat = DateTime::ATOM,
        string $delimiter = ',',
        string $enclosure = '"'
    ) {
        $this->dateFormat = $dateFormat;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    public function format(string $level, string|\Stringable $message, array $context = []): string
    {
        $timestamp = date($this->dateFormat);
        $level = strtoupper($level);
        $contextJson = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        $fields = [
            $timestamp,
            $level,
            (string) $message,
            $contextJson
        ];

        // Build a CSV line manually to preserve custom delimiter/enclosure
        $escapedFields = array_map(fn($field) => $this->enclosure . str_replace($this->enclosure, $this->enclosure . $this->enclosure, $field) . $this->enclosure, $fields);

        return implode($this->delimiter, $escapedFields);
    }
}
