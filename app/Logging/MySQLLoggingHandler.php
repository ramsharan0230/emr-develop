<?php

namespace App\Logging;
// use Illuminate\Log\Logger;

use App\ActivityLog;
use DB;
use Monolog\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Monolog\Handler\AbstractProcessingHandler;

class MySQLLoggingHandler extends AbstractProcessingHandler
{
    /**
     *
     * Reference:
     * https://github.com/markhilton/monolog-mysql/blob/master/src/Logger/Monolog/Handler/MysqlHandler.php
     */
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        $this->table = 'activity_log_new';
        parent::__construct($level, $bubble);
    }
    protected function write(array $record): void
    {
        $route['url'] = Route::current();
        $route['name'] = Route::currentRouteName();
        $route['action'] = Route::currentRouteAction();
        $type =  json_decode($record['message'])[1] ?? Null;

        $user_id = null;
        if ($type && $type == 'Access') {
            $user_id = json_decode($record['message'])[2] ?? Null;
        }
        $data = array(
            'message'       => json_decode($record['message'])[0] ?? Null,
            'context'       => json_encode($record['context']),
            'level'         => $record['level'],
            'level_name'    => $record['level_name'],
            'channel'       => $record['channel'],
            'type'          => $type,
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra'         => json_encode($record['extra']),
            'route'         => json_encode($route),
            'formatted'     => $record['formatted'],
            'remote_addr'   => $_SERVER['REMOTE_ADDR'],
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
            'user_id'       => Auth::guard("admin_frontend")->id() ?? $user_id,
            'created_at'    => date("Y-m-d H:i:s"),
        );
        ActivityLog::create($data);
        unset($route, $type, $user_id, $data);
    }
}
