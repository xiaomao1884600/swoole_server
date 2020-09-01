<?php

namespace App\Console\Commands;

use App\Service\Mes\ClientService;
use Illuminate\Console\Command;

class Client extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'swoole客户端';

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
    public function handle(ClientService $clientService)
    {
        //发送消息
        return $clientService->sendMes();
    }
}
