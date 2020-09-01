<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/5/18
 * Time: 下午2:24
 */

namespace App\Http\Controllers\Mes;


use App\Http\Controllers\Controller;
use App\Service\Exceptions\ApiExceptions;
use App\Service\Exceptions\Message;
use App\Service\Mes\ClientService;
use App\Service\Mes\SocketClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {

    }

    public function sendMes(Request $request, ClientService $clientService)
    {
        // 发送消息
        try {
            return Message::success($clientService->sendMes(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }

    public function sendSocketMes(Request $request, SocketClientService $clientService)
    {
        // 发送消息
        try {
            return $clientService->sendMes(requestData($request));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }

    public function sendUserMes(Request $request, SocketClientService $clientService)
    {
        // 发送消息
        try {
            return $clientService->sendUserMes(requestData($request));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }
}