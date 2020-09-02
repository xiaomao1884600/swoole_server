<?php

namespace App\Console\Commands;

use App\Service\Swoole\SwooleGoService;
use Illuminate\Console\Command;

class SwooleGoServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:goserver {--close}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '协程';

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

        $swooleService = app(SwooleGoService::class, ['params' => $params]);

        // 启动服务
        $swooleService->onReceive();
    }
}
