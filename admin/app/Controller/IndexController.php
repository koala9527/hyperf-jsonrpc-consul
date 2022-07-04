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

class IndexController extends AbstractController
{
    /**
     * 前台服务
     * @Inject 
     * @var AppServiceInterface
     */
    protected $app_service_interface;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $msg = $this->app_service_interface->getTestMsg($user);
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
