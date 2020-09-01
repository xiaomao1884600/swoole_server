<?php
/**
 * {NAME}
 * Created by wwj
 * Date: 2020-07-15 11:36
 */
namespace App\Repository\Redis;

use App\Repository\Foundation\BaseRep;

class OrderRepository extends BaseRep
{
    protected $host = '127.0.0.1';
    protected $port = '6379';
    protected $goodListKey = '20200715_goods_list';
    public function __construct()
    {

    }

    public function addGoods (array $params = [])
    {
        $redis = new \Redis();
        $redis->connect($this->host, $this->port);
        $goods = 100;
        $goodListKey = $this->goodListKey;
        for ($i = 1; $i <= $goods; $i ++){
            $redis->rPush($goodListKey, $i);
        }

        return ["goods_list_total" => $goods];
    }


    public function makeOrder (array $params = [])
    {
        $redis = new \Redis();
        $redis->connect($this->host, $this->port);
        $uuid = md5(uniqid('user') . time());
        $goodListKey = $this->goodListKey;
        $orderKey = '20200715_buy_order';
        $failUserNum = '20200715_fail_user_num';
        $failUser = '20200715_fail_user_list';
        if($goodId = $redis->lpop($goodListKey)){
            $redis->hSet($orderKey, $goodId, $uuid);
        }else{
            // 失败数
            $redis->incr($failUserNum);
//            $redis->set($failUser, 0, $uuid);
        }

        return ['success'];
    }

}