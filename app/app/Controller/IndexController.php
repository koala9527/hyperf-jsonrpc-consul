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
use  App\JsonRpc\AdminServiceInterface;

class IndexController extends AbstractController
{

    
    /**
     * 后台服务
     * @Inject 
     * @var AdminServiceInterface
     */
    protected $admin_service_interface;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        $msg = $this->admin_service_interface->getTestMsg($user);
        return [
            'method' => $method,
            'message' => "Hello {$msg}.",
        ];
    }
}
