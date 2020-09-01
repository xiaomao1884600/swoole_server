<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/5/15
 * Time: 下午2:30
 */
namespace App\Service\Mes;

use App\Service\Foundation\BaseService;

class MesService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMes(array $params)
    {
        // 接收消息

        return ['hehe'];
    }
}