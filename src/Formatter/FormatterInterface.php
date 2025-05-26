<?php

namespace Bibo\Logger\Formatter;

use Stringable;

interface FormatterInterface
{
    public function format(string $level, string|Stringable $message, array $context = []): string;
}
