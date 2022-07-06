<?php

namespace App\JsonRpc;

use Hyperf\RpcClient\AbstractServiceClient;

interface AdminServiceInterface
{
    /**
     * @param string $msg 测试消息
     */
    public function getTestMsg(string $msg);
}