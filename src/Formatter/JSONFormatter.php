<?php

namespace Bibo\Logger\Formatter;

class JSONFormatter implements FormatterInterface
{
    protected string $dateFormat;
    protected bool $prettyPrint;

    public function __construct(string $dateFormat = \DateTime::ATOM, bool $prettyPrint = false)
    {
        $this->dateFormat = $dateFormat;
        $this->prettyPrint = $prettyPrint;
    }

    public function format(string $level, string|\Stringable $message, array $context = []): string
    {
        $record = [
            'timestamp' => date($this->dateFormat),
            'level' => strtoupper($level),
            'message' => (string) $message,
        ];

        if (!empty($context)) {
            $record['context'] = $context;
        }

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if ($this->prettyPrint) {
            $flags |= JSON_PRETTY_PRINT;
        }

        return json_encode($record, $flags);
    }
}
