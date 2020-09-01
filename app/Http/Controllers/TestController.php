<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct()
    {

    }

    public function testp (Request $request)
    {
//        $result = sprintf("%06.2f", 13.1); // "013.10"  0 代表坐填充，6代表6位，从右边往左边计算
        $result = sprintf("%01.2f", 13.3); // "013.10"  0 代表坐填充，6代表6位，从右边往左边计算
        debuger($result);
        // "使用占位符，当百分号多于参数时， 保留小数 13.30, 不使用小数时为13", %号多于参数时必须使用占位符，占位符用%后面跟数字和'\$'
        $result = sprintf("使用占位符，当百分号多于参数时， 保留小数 %1\$.2f, 不使用小数时为%1\$u", 13.3); // ""  % 代表坐填充，6代表6位，从右边往左边计算
        dd($result);

        return $result;
    }
}