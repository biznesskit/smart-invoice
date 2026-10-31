<?php

namespace App\Logging;

use Illuminate\Support\Facades\Storage;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class CloudLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('cloud');

        // Retrieve the disk name from the configuration array
        $disk = $config['disk'];

        if($disk == 'local' || $disk == 'public') return;

        // Get the specified disk storage instance
        $storage = Storage::disk($disk);
        $logFile =  'logs' . '/' . env('CLOUD_LOGS_DIR', 'api') . '/' . date('Y-m-d') . '.log';
        
        // Create a stream for the log file
        $writeStream = fopen('php://temp', 'r+');

        // Configure the StreamHandler to use the memory stream
        $handler = new StreamHandler($writeStream);
        $handler->setFormatter(new LineFormatter(null, null, true, true));
        $logger->pushHandler($handler);

        // Write the log contents to the storage after logging
        register_shutdown_function(function () use ($writeStream, $storage, $logFile) {
            rewind($writeStream);
            $contents = stream_get_contents($writeStream);

            if ($storage->exists($logFile)) {
                $storage->append($logFile, $contents);
            } else {
                $storage->put($logFile, $contents);
            }

            fclose($writeStream);
        });
        return $logger;
    }
}
