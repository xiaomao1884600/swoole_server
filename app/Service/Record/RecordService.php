<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/4/9
 * Time: 下午4:34
 */
namespace App\Service\Record;

use App\Service\Foundation\BaseService;

class RecordService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setCallOut(array $params)
    {
        dd($params);
    }
}