<?php

namespace App\Logging;

use Monolog\Logger;

class MySQLCustomLogger
{
    /**
     * Create a custom Monolog instance.
     *
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        // dd($config);
        $logger = new Logger("Cogent-Health");
        return $logger->pushHandler(new MySQLLoggingHandler());
    }
}
