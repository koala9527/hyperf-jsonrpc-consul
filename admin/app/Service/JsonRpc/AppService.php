<?php

declare(strict_types=1);

namespace App\Service\JsonRpc;

use Hyperf\RpcServer\Annotation\RpcService;

/**
 * 产品服务移动端
 * @RpcService(name="AdminAppService", protocol="jsonrpc", server="jsonrpc", publishTo="consul")
 */
class AppService 
{
    public function makeMsg(string $msg)
    {
        return 'from_admin_rpc:'.$msg;
    }
}