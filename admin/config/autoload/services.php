<?php
return [
  'consumers' => value(function () {
    $consumers = [];
    $services = [
      'AppAdminService' => App\JsonRpc\AppServiceInterface::class,
      'GolangApiService' => App\JsonRpc\GolangServiceInterface::class,
    ];
    foreach ($services as $name => $interface) {
      $protocol = 'jsonrpc';
      if ($name == 'GolangApiService') {
        $protocol = 'jsonrpc-http';
    }
      $consumers[] = [
        // name 需与服务提供者的 name 属性相同
        'name' => $name,
        // 服务接口名，可选，默认值等于 name 配置的值，如果 name 直接定义为接口类则可忽略此行配置，如 name 为字符串则需要配置 service 对应到接口类
        'service' => $interface,
        // 服务提供者的服务协议，可选，默认值为 jsonrpc-http
        'protocol' => $protocol,
        // 负载均衡算法，可选，默认值为 random
        'load_balancer' => 'random',
        // 这个消费者要从哪个服务中心获取节点信息，如不配置则不会从服务中心获取节点信息
        'registry' => [
          'protocol' => 'consul',
          'address' =>  env('CONSUL_URL', 'http://127.0.0.1:8500'),
        ],
        // 配置项，会影响到 Packer 和 Transporter
        'options' => [
          'connect_timeout' => 5.0,
          'recv_timeout' => 5.0,
          'settings' => [
            // 根据协议不同，区分配置
            'open_eof_split' => true,
            'package_eof' => "\r\n",
            // 'open_length_check' => true,
            // 'package_length_type' => 'N',
            // 'package_length_offset' => 0,
            // 'package_body_offset' => 4,
          ],
          // 当使用 JsonRpcPoolTransporter 时会用到以下配置
          'pool' => [
            'min_connections' => 1,
            'max_connections' => 96,
            'connect_timeout' => 20.0,
            'wait_timeout' => 20.0,
            'heartbeat' => -1,
            'max_idle_time' => 60.0,
          ],
        ],
      ];
    }
    return $consumers;
  }),
  'providers' => [],
  'drivers' => [
      'consul' => [
          'uri' => env('CONSUL_URL', 'http://127.0.0.1:8500'),	//此处为自己的consul地址
          'token' => '',
          'check' => [
              'deregister_critical_service_after' => '90m',
              'interval' => '1s',
          ],
      ],
  ],
];
