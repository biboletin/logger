<?php

namespace Bibo\Logger\Handler;

class RotatingFileHandler
{
    protected $directory;
    protected $filename;
    protected $maxFiles;
    
    public function __construct(string $directory, string $filename = 'app.log', int $maxFiles = 5)
    {
        $this->directory = rtrim($directory, '/');
        $this->filename = $filename;
        $this->maxFiles = $maxFiles;

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }
        
        $this->rotateLogs();
    }
    
    public function write(string $message): void
    {
        $date = date('Y-m-d');
        $logFile = "{$this->directory}/{$date}-{$this->filename}";
        
        file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    protected function rotateLogs(): void
    {
        $files = glob("{$this->directory}/*-{$this->filename}");
        
        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));

        if (count($files) >= $this->maxFiles) {
            foreach (array_slice($files, $this->maxFiles) as $file) {
                unlink($file);
            }
        }
    }
}
