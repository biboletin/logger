<?php

use Bibo\Logger\Formatter\LineFormatter;
use Bibo\Logger\Handler\RotatingFileHandler;
use Bibo\Logger\Logger;

include __DIR__ . '/vendor/autoload.php';

$logger = new Logger(
    new LineFormatter(),
    new RotatingFileHandler(__DIR__ . '/logs', 'app.log', 10)
);

$logger->info("User {user} logged in", ['user' => 'admin']);
$logger->error("Syntax error", ['file' => 'index.php', 'line' => 42]);

dd($logger);
