<?php

namespace App\JsonRpc;

interface AdminServiceInterface
{
    /**
     * @param string $msg 测试消息
     */
    public function getTestMsg(string $msg);
}