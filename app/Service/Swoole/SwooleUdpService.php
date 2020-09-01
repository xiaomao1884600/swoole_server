<?php
/**
 * {NAME}
 * Created by wwj
 * Date: 2020-08-31 15:31
 */

namespace App\Service\Swoole;


use App\Service\Foundation\BaseService;

class SwooleUdpService extends BaseService
{
    protected $serv;
    public function __construct($params = [])
    {
        parent::__construct();
        error_reporting(0);
        $this->init_udp($params);
    }

    public function init_udp($params)
    {
        $this->serv = new \swoole_server('0.0.0.0', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mod' => 1,
        ]);

//        $this->serv->on('Start', [$this, 'OnStart']);
//        $this->serv->on('Connect', [$this, 'onConnect']);
        $this->serv->on('Packet', [$this, 'onPacket']);
//        $this->serv->on('Close', [$this, 'onClose']);

        $this->serv->start();

//        // 检测是否关闭
//        $close = $params['close'] ?? false;
//
//        if($close){
//            $this->serv->close();
//        }

    }

    public function onStart($serv)
    {
        echo "swoole Start \r\n";
    }

    public function onConnect($serv, $fd, $from_id)
    {
        echo "swoole Connect, fd: {$fd}, from_id: {$from_id} \r\n";
    }

    public function onPacket($serv, $data, $clientInfo)
    {
        echo "server udp : getMes from address: {$clientInfo['address']}, port: {$clientInfo['port']}, data:{$data} " . PHP_EOL;
        // send to client $fd
        $serv->sendto($clientInfo['address'], $clientInfo['port'], 'Server：' . $data . PHP_EOL);
    }

    public function OnClose($serv, $fd, $from_id)
    {
        echo "client fd : {$fd} closed connection \r\n";
    }

    public function testStart(array $params)
    {
        echo "test start \r\n";
    }
}