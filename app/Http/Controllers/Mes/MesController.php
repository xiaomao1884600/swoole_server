<?php
/**
 * Created by PhpStorm.
 * User: wangwujun
 * Date: 2018/5/15
 * Time: 下午2:25
 */

namespace App\Http\Controllers\Mes;


use App\Http\Controllers\Controller;
use App\Service\Exceptions\ApiExceptions;
use App\Service\Exceptions\Message;
use App\Service\Mes\MesService;
use Illuminate\Http\Request;

class MesController extends Controller
{
    public function __construct()
    {

    }

    public function getMes(Request $request, MesService $mesService)
    {
        // 接收消息
        try {
                return Message::success($mesService->getMes(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }
}