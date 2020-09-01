<?php
/**
 * {NAME}
 * Created by wwj
 * Date: 2020-08-31 18:11
 */

namespace App\Service\Mes;


use App\Service\Foundation\BaseService;
use App\Service\Swoole\SwooleSocketService;
use App\Tools\Functions;

class SocketClientService extends BaseService
{
    protected $client;

    public function __construct(
    )
    {
        parent::__construct();
    }

    public function sendMes(array $params = [])
    {
        // 发送消息
        return view('swoole.socket');

    }

    public function sendUserMes()
    {
        $url = 'http://127.0.0.1:9503';
        $data = [
            'scene' => '你的东西掉了',
        ];
//        $data = json_encode($data);
        $res = curlRequest($url, $data, 'POST');
        dd($res);
    }
}