<?php

namespace App\Console\Commands;

use App\Service\Swoole\SwooleSocketService;
use Illuminate\Console\Command;

class SwooleSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:socketserver {--close}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'websocket 监听服务';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = $this->arguments();
        $params['close'] = $this->option('close');

        $swooleService = app(SwooleSocketService::class, ['params' => $params]);

        // 启动服务1
        $swooleService->onMessage();
    }
}
