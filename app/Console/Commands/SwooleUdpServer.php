<?php

namespace App\Console\Commands;

use App\Service\Swoole\SwooleUdpService;
use Illuminate\Console\Command;

class SwooleUdpServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:udpserver {--close}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '启动swoole udp 服务';

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

        $swooleService = app(SwooleUdpService::class, ['params' => $params]);

        // 启动服务1
        $swooleService->onPacket();
    }
}
