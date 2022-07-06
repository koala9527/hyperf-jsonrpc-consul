<?php

declare(strict_types=1);

namespace App\Service\JsonRpc;

use Hyperf\RpcServer\Annotation\RpcService;

/**
 * 产品服务移动端
 * @RpcService(name="AppAdminService", protocol="jsonrpc", server="jsonrpc", publishTo="consul")
 */
class AdminService 
{
    public function getTestMsg(string $msg)
    {
        return 'from_app_rpc:'.$msg;
    }
}