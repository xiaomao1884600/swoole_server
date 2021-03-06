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
        $this->serv->on('task', [$this, 'onTask']);
        $this->serv->on('finish', [$this, 'onFinish']);
        $this->serv->on('Close', [$this, 'onClose']);

        // 设置异步任务的工作进程数量 20200902
        $this->serv->set(array('task_worker_num' => 4));

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

        // 尝试使用异步任务  20200902
        $task_id = $serv->task($data);

        echo "Dispatch AsyncTask id:{$task_id} result" . PHP_EOL;
    }

    public function OnClose($serv, $fd, $from_id)
    {
        echo "client fd : {$fd} closed connection \r\n";
    }

    public function testStart(array $params)
    {
        echo "test start \r\n";
    }

    public function onTask ($serv, $task_id, $from_id, $data)
    {
        sleep(5);
        echo "New Task[id={$task_id}]  [from_id={$from_id}]" . PHP_EOL;
        $serv->finish("$data -> OK");
    }

    public function onFinish($serv, $task_id, $data)
    {
        echo "AsyncTask [{$task_id}] Finish: {$data}" . PHP_EOL;
        $serv->send(1, 'Server finish: over -'. $data . PHP_EOL);
    }

}