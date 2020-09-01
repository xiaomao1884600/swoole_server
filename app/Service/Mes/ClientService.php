<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/5/18
 * Time: 下午2:25
 */

namespace App\Service\Mes;


use App\Service\Foundation\BaseService;

class ClientService extends BaseService
{
    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function init()
    {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
        //debuger($this->client);
        if(! $this->client->connect('127.0.0.1', 9501, 1)){
            echo "Error: connect failed";
        }

    }

    public function recvMes(array $params = [])
    {
        $message = $this->client->recv();
        echo "recv server mes : {$message}";
    }

    public function sendMes(array $params = [])
    {
        // 发送消息
        fwrite(STDOUT, "请输入消息：");
        $msg = trim(fgets(STDIN));
        $this->client->send( $msg );

        fwrite(STDOUT, "请输入姓名：");
        $msg = trim(fgets(STDIN));
        $this->client->send( $msg );
    }
}