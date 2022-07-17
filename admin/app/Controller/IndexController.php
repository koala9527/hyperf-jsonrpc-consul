<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use  App\JsonRpc\AppServiceInterface;
use  App\JsonRpc\GolangServiceInterface;
use  App\JsonRpc\PythonServiceInterface;

class IndexController extends AbstractController
{
    /**
     * 前台服务
     * @Inject 
     * @var AppServiceInterface
     */
    protected $app_service_interface;

    /**
     * golang服务
     * @Inject 
     * @var GolangServiceInterface
     */
    protected $golang_service_interface;

    /**
     * python服务
     * @Inject
     * @var PythonServiceInterface
     */
    protected $python_service_interface;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $msg = $this->app_service_interface->getTestMsg($user);
        return [
            'method' => $method,
            'message' => "Hello {$msg}.",
        ];
    }

    public function golang()
    {
        $user = $this->request->input('msg', 'Hyperf');
        $method = $this->request->getMethod();
        $msg = $this->golang_service_interface->getMsg($user);
        return [
            'method' => $method,
            'message' => "Hello {$msg}.",
        ];
    }
    public function python()
    {
        $user = $this->request->input('msg', 'Hyperf');
        $method = $this->request->getMethod();
        $msg = $this->python_service_interface->getMsg($user);
        return [
            'method' => $method,
            'message' => "Hello {$msg}.",
        ];
    }
}
