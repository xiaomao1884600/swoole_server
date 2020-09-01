<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/5/15
 * Time: 下午2:07
 */
namespace App\Service\Swoole;

use App\Service\Foundation\BaseService;
use Illuminate\Http\Request;

class SwooleService extends BaseService
{
    protected $serv;
    public function __construct($params = [])
    {
        parent::__construct();
        error_reporting(0);
        $this->init_tcp($params);
    }

    public function init_tcp($params)
    {
        $this->serv = new \swoole_server('0.0.0.0', 9501);

        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mod' => 1,
        ]);

        $this->serv->on('Start', [$this, 'OnStart']);
        $this->serv->on('Connect', [$this, 'onConnect']);
        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Close', [$this, 'onClose']);

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

    public function onReceive($serv, $fd, $from_id, $data)
    {
        echo "server: getMes from fd: {$fd}, from_id: {$from_id}, data:{$data} " . PHP_EOL;
        // send to client $fd
        $serv->send($fd, 'Server: ni mei de-'. $data . PHP_EOL);
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