<?php
/**
 * {NAME}
 * Created by wwj
 * Date: 2020-07-15 11:31
 */
namespace App\Http\Controllers\Redis;

use App\Http\Controllers\Controller;
use App\Repository\Redis\OrderRepository;
use App\Service\Exceptions\ApiExceptions;
use App\Service\Exceptions\Message;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 下单
     * Created by wwj
     * Date: 2020-07-15 11:38
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @return array|mixed
     */
    public function addGoods (Request $request, OrderRepository $orderRepository)
    {
        try {
            return Message::success($orderRepository->addGoods(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }

    /**
     * 下单
     * Created by wwj
     * Date: 2020-07-15 11:38
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @return array|mixed
     */
    public function makeOrder (Request $request, OrderRepository $orderRepository)
    {
        // 秒杀
        try {
            return Message::success($orderRepository->makeOrder(requestData($request)));
        } catch (\Exception $exception) {
            return ApiExceptions::handle($exception);
        }
    }
}