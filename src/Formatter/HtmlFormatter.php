<?php

namespace Bibo\Logger\Formatter;

/**
 * HtmlFormatter formats log entries as HTML.
 *
 * It includes a timestamp, log level, message, and optional context.
 * The output is suitable for rendering in a web browser.
 *
 * .log-entry { margin-bottom: 1em; font-family: monospace; padding: 0.5em; border: 1px solid #ccc; }
 * .log-info { background-color: #e7f4e4; }
 * .log-error { background-color: #fbeaea; }
 * .log-warning { background-color: #fff6d1; }
 * .log-debug { background-color: #f0f0f0; }
 * .log-context { font-size: 0.9em; color: #555; }
 */
class HtmlFormatter implements FormatterInterface
{
    protected string $dateFormat;

    public function __construct(string $dateFormat = \DateTime::ATOM)
    {
        $this->dateFormat = $dateFormat;
    }

    public function format(string $level, string|\Stringable $message, array $context = []): string
    {
        $timestamp = date($this->dateFormat);
        $level = strtoupper($level);
        $message = htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8');
        $contextJson = !empty($context)
            ? '<pre class="log-context">' . htmlspecialchars(
                json_encode($context, JSON_PRETTY_PRINT),
                ENT_QUOTES,
                'UTF-8'
            ) . '</pre>'
            : '';

        return <<<HTML
<div class="log-entry log-{$level}">
    <span class="log-timestamp">{$timestamp}</span>
    <span class="log-level">{$level}</span>
    <span class="log-message">{$message}</span>
    {$contextJson}
</div>
HTML;
    }
}
