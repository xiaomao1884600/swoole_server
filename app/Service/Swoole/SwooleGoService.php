<?php
/**
 * {NAME}
 * Created by wwj
 * Date: 2020-09-02 14:50
 */

namespace App\Service\Swoole;


use App\Service\Foundation\BaseService;

class SwooleGoService extends BaseService
{
    protected $serv;
    public function __construct($params = [])
    {
        parent::__construct();
        error_reporting(0);
        $this->init_go($params);
    }

    public function init_go($params)
    {
        // 使用tcp方式或者socket方式，注意new的服务类
        $this->serv = new \Swoole\Http\Server('0.0.0.0', 9504, SWOOLE_BASE);
//        $this->serv = new \Swoole\WebSocket\Server('0.0.0.0', 9504);

        $this->serv->set([
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mod' => 1,
        ]);

        $this->serv->on('Connect', function ($serv, $fd, $from_id){
            echo "swoole Connect, fd: {$fd}, from_id: {$from_id} \r\n";
        });

        $this->serv->on('Message', function ($serv, $frame){
            echo "socket server: data:{$frame->data} " . PHP_EOL;
            // send to client $fd
            $serv->push($frame->fd, 'Socket Server : '. $frame->data . PHP_EOL);
        });

        $this->serv->on('Receive', function ($serv, $fd, $from_id, $data){
            $serv->send($fd, 'Server: go run -'. $data . PHP_EOL);
        });

//        $this->serv->on('Request', function ($request, $response){
//            echo "socket server onRequest: data:{$request->post['scene']}" . PHP_EOL;
//            $connections = $this->serv->connections;
//            foreach($connections as $fd){
//                $this->serv->push($fd, 'Socket Server onRequest : '. $request->post['scene'] . PHP_EOL);
//            }
//        });

        // 协程
//        $this->serv->on('Request', function($request, $response){
//            $mysql = new \Swoole\Coroutine\MySQL();
//            $res = $mysql->connect([
//                'host' => 'localhost',
//                'user' => 'root',
//                'password' => 'root',
//                'database' => 'upgrade_db',
//            ]);
//            if($res == false){
//                $response->end("Mysql connect fail");
//                return;
//            }
//
//            $ret = $mysql->query("show tables", 2);
//            $response->end("swoole response is ok, result = " . var_export($ret, true));
//
//        });

        /**
        $n = 4;
        for ($i = 0; $i < $n; $i++) {
            go(function () use ($i) {
            // 模拟 IO 等待
            \Co::sleep(1);
            echo microtime(true) . ": hello $i " . PHP_EOL;
            });
        };

         */

        // 协程，使用\Co::sleep(10), 打印出来的时间一致,开始时间 跟结束时间一致，基本立马返回，然后协程异步执行数据库查询
        $this->serv->on('Request', function($request, $response){
            $cont = 10;
            echo "START microtime:  " . microtime(true) . PHP_EOL;
            for($i = 0; $i < $cont; $i ++) {
                go(function () use($request, $response, $i){
                    // 模拟 IO 等待
                    \Co::sleep(10);
                    $mysql = new \Swoole\Coroutine\MySQL();
                    $res = $mysql->connect([
                        'host' => 'localhost',
                        'user' => 'root',
                        'password' => 'root',
                        'database' => 'upgrade_db',
                    ]);
                    if ($res == false) {
                        $response->end("Mysql connect fail");
                        return;
                    }
                    $ret = $mysql->query("select sleep(5)");
                    echo "microtime:  " . microtime(true) . " swoole response is ok, result = " . $i . PHP_EOL;
                });
            };

            echo "END microtime:  " . microtime(true) . PHP_EOL;
        });

        $this->serv->on('Close', function($serv, $fd){
            echo "Client [{$fd}] closed" . PHP_EOL;
        });

        $this->serv->start();
    }


}