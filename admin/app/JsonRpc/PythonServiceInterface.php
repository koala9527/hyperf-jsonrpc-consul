<?php

namespace App\JsonRpc;

interface PythonServiceInterface
{
    /**
     * @param string $msg 测试消息
     */
    public function getMsg(string $msg);
}