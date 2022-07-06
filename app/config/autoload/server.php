<?php

declare(strict_types=1);

use Hyperf\Server\Server;
use Hyperf\Server\Event;
use Swoole\Constant;

return [
    'mode' => SWOOLE_PROCESS,
    'servers' => [
        [
            'name' => 'http',
            'type' => Server::SERVER_HTTP,
            'host' => '0.0.0.0',
            'port' => 9601,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                Event::ON_REQUEST => [Hyperf\HttpServer\Server::class, 'onRequest'],
            ],
        ],
        [
            'name' => 'jsonrpc',
            'type' => Server::SERVER_BASE,
            'host' => '0.0.0.0',
            'port' => 9602,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                Event::ON_RECEIVE => [\Hyperf\JsonRpc\TcpServer::class, 'onReceive'],
            ],
            'settings' => [
                'open_eof_split' => true,
                'package_eof' => "\r\n",
            ],
        ],
    ],
    'settings' => [
        Constant::OPTION_ENABLE_COROUTINE => true,
        Constant::OPTION_WORKER_NUM => env('APP_ENV') == 'prod' ? swoole_cpu_num() : 2,
        Constant::OPTION_PID_FILE => BASE_PATH . '/runtime/hyperf.pid',
        Constant::OPTION_OPEN_TCP_NODELAY => true,
        Constant::OPTION_MAX_COROUTINE => 100000,
        Constant::OPTION_OPEN_HTTP2_PROTOCOL => true,
        Constant::OPTION_MAX_REQUEST => 100000,
        // Constant::OPTION_SOCKET_BUFFER_SIZE => 20 * 1024 * 1024,
        // Task Worker 数量，根据您的服务器配置而配置适当的数量
        Constant::OPTION_TASK_WORKER_NUM => env('APP_ENV') == 'prod' ? 8 : 1,
        // 因为 `Task` 主要处理无法协程化的方法，所以这里推荐设为 `false`，避免协程下出现数据混淆的情况
        Constant::OPTION_TASK_ENABLE_COROUTINE => false,
        //最大上传文件大小
        Constant::OPTION_PACKAGE_MAX_LENGTH => 2 * 1024 * 1024 * 1024,
        //缓存区域
        Constant::OPTION_BUFFER_OUTPUT_SIZE => 20 * 1024 * 1024,
        // 静态资源
        // Constant::OPTION_DOCUMENT_ROOT => BASE_PATH . '/public',

        Constant::OPTION_ENABLE_STATIC_HANDLER => true,
        //Constant::OPTION_STATIC_HANDLER_LOCATIONS => ['/'],
    ],
    'callbacks' => [
        Event::ON_BEFORE_START => [Hyperf\Framework\Bootstrap\ServerStartCallback::class, 'beforeStart'],
        Event::ON_WORKER_START => [Hyperf\Framework\Bootstrap\WorkerStartCallback::class, 'onWorkerStart'],
        Event::ON_PIPE_MESSAGE => [Hyperf\Framework\Bootstrap\PipeMessageCallback::class, 'onPipeMessage'],
        Event::ON_WORKER_EXIT => [Hyperf\Framework\Bootstrap\WorkerExitCallback::class, 'onWorkerExit'],
        // Task callbacks
        Event::ON_TASK => [Hyperf\Framework\Bootstrap\TaskCallback::class, 'onTask'],
        Event::ON_FINISH => [Hyperf\Framework\Bootstrap\FinishCallback::class, 'onFinish'],
    ],
];
