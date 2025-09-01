<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\JsonFormatter;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke(Logger $logger): void
    {
        // フォーマットを指定
        $formatter = new JsonFormatter();
        // trace追加
        $formatter->includeStacktraces(true);
        $formatter->setJsonPrettyPrint(true);

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
        }
    }
}
