<?php
/**
 * {NAME}
 * Created by wwj
 * Date: 2020-08-31 18:00
 */

namespace App\Service\Swoole;


use App\Service\Foundation\BaseService;

class SwooleSocketService extends BaseService
{
    protected $serv;
    public function __construct($params = [])
    {
        parent::__construct();
        error_reporting(0);
        $this->init_socket($params);
    }

    public function init_socket($params)
    {
        $this->serv = new \Swoole\WebSocket\Server('0.0.0.0', 9503);

        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mod' => 1,
        ]);

        $this->serv->on('Start', [$this, 'OnStart']);
        $this->serv->on('open', [$this, 'onOpen']);
        $this->serv->on('request', [$this, 'onRequest']);
        $this->serv->on('message', [$this, 'onMessage']);
        $this->serv->on('close', [$this, 'onClose']);

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

    public function onOpen($serv, $request)
    {
        echo "socket server, fd: {$request->fd}, socket \r\n" . PHP_EOL;
        $serv->push($request->fd, 'socket Server connect success : ' . PHP_EOL);

    }

    public function onMessage($serv, $frame)
    {
        echo "socket server: data:{$frame->data} " . PHP_EOL;
        // send to client $fd
        $serv->push($frame->fd, 'Socket Server : '. $frame->data . PHP_EOL);
    }

    public function onRequest($request, $response)
    {
        echo "socket server onRequest: data:{$request->post['scene']}" . PHP_EOL;
        $connections = $this->serv->connections;
        foreach($connections as $fd){
            $this->serv->push($fd, 'Socket Server onRequest : '. $request->post['scene'] . PHP_EOL);
        }
    }

    public function OnClose($serv, $fd)
    {
        echo "client fd : {$fd} closed connection \r\n";
    }

    public function testStart(array $params)
    {
        echo "test start \r\n";
    }
}