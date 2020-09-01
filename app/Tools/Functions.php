<?php
use Illuminate\Http\Request;
use App\Repositories\Log\LogService;
use Illuminate\Support\Facades\Log;

if (!function_exists('responseJson'))
{
    /**
     * Return a new response from the application.
     * @param type $content
     * @param type $code
     * @param type $message
     * @param type $options = JSON_FORCE_OBJECT 使用非关联数组时输出一个对象
     * @return type
     */
    function responseJson($content = '', $code = 200, $message = '', $success = true, $options = JSON_ERROR_NONE)
    {
        $data = [
                'success' => $success,
                'errorMessage' => $message,
                'errorCode' => $code,
                'data' => $content
        ];
        //LogService::setResponseTime(); //记录响应时间
        //本次请求结束后记录请求日志到阿里云  2018年06月27日
        LogService::saveRequestAction();

        return response()->json($data, 200, [], $options);
    }
}

if (!function_exists('responseApiJson'))
{
	/**
	 * api接口返回格式
	 * @param type $content
	 * @param type $code
	 * @param type $message
	 * @param type $options = JSON_FORCE_OBJECT 使用非关联数组时输出一个对象
	 * @return type
	 */
    function responseApiJson($content = '',$returnType=1, $code = 200, $message = '', $success = true)
    {
        $data = [];
        if(1==$returnType)
        {
           $data=$content;
        }
        else
        {
           $data = [
                'success' => $success,
                'errorMessage'=>$message,
                'errorCode'=> $code,
                'data' => $content
             ]; 
        }
        return response()->json($data, 200, [], JSON_ERROR_NONE);
    }
}
if (! function_exists('sslEncrypt')) {
    /**
     * sslEncrypt
     * 数据加密
     * @param string $msg 
     * @param string $secretKey 
     * @access public
     * @return string
     */
    function sslEncrypt($msg, $secretKey)
    {
        //$iv = hex2bin(md5(time()));
        $iv = openssl_random_pseudo_bytes(16);
        $method = "aes-128-cbc";
        if(function_exists('openssl_encrypt')){
            $ret = urlencode(openssl_encrypt(urlencode($msg), $method, $secretKey, false, $iv));
        }else{
            $ret = urlencode(exec("echo \"".urlencode($msg)."\" | openssl enc -".urlencode($method)." -base64 -nosalt -K ".bin2hex($secretKey)." -iv ".bin2hex($iv)));
        }
        return base64_encode($iv.$ret);
    }
}

if(!function_exists('sendError')) {
    /**
     * 发送错误消息提示
     * 
     * @param string  $error 错误信息提示
     * @param integer $code  状态码
     * @param array   $data  数据
     * @return json
     */
    function sendError($error, $code = 404, $data = [])
    {
        $errCode = $code ?: 201;
        $res = [
            'success' => false,
            'errorMessage' => $error,
            'errorCode' => $errCode,
            'data' => [],
        ];
        if (!empty($data)) {
            $res['data'] = $data;
        }
        return response()->json($res, $errCode);
    }
}

if (!function_exists('requestData'))
{
    /**
     * 获取请求数据中的data
     * @param type $request
     * @param type $key
     * @param type $column
     * @return type
     */
    function requestData($request, $key = '', $column = 'data')
    {
        if (!is_object($request)) return [];
        $requestInfo = $request->all();
        if (! array_key_exists($column, $requestInfo) || ! is_array($requestInfo[$column])) return [];
        $requestInfo = $requestInfo[$column];
        if ($key)
        {
            return array_key_exists($key, $requestInfo) ? $requestInfo[$key] : ''; 
        }
        return $requestInfo; 
    }
}

if (!function_exists('requestApiData'))
{
    /**
     * api接口参数
     * @param type $request
     * @param type $key
     * @param type $column
     * @return type
     */
    function requestApiData ($request, $key = '', $column = '')
    {
        if (!is_object($request)){
            $requestInfo = [];
        } else {
            $requestInfo = $request->all();
            if ($key){
                $requestInfo = array_key_exists($key, $requestInfo) ? $requestInfo[$key] : '';
            }
        }
        Log::info('request_params',[$requestInfo]);
        return $requestInfo;
    }
}

/**
 * 匹配网址
 * Markdown 作者多年改进的正则匹配
 * 基本能匹配绝大部分网站
 */
if (!function_exists('pregUrl')) {
    function pregUrl($url) {
        $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';

        return preg_match($regex, $url);
    }
}

/**
 * 匹配手机号
 */
if (!function_exists('pregPhone')) {
    function pregPhone($phone) {
       // $regex = '/^13\d{9}$|^14\d{9}$|^15\d{9}$|^17\d{9}$|^18\d{9}$/';
        $regex = '/^1[3456789][0-9]{9}$/';
        return preg_match($regex,$phone);
    }
}
/**
 * 匹配邮箱
 */
if (!function_exists('pregEmail')) {
    function pregEmail($email) {
        $regex = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i"; //i 忽略大小写
        return preg_match($regex,$email);
    }
}

/**
 * 生成ajax返回数据
 */
if (!function_exists('jsonMsg')) {
    function jsonMsg($info = 'ok', $data = []) {
        $json = ['info'=>$info];
        $json['data'] = $data;
        return response()->json($json);
    }
}

/**
 * 生成api返回数据
 */
if (!function_exists('apiResult')) {
    function apiResult($info = 'ok', $data = [], $code = '200') {
        $result = [
            'status' => 'ok',
            'code'   => '200',
            'data'   => $data
        ];

        if ($info != 'ok') {
            $result = [
                'status' => $info,
                'code'   => $code != '200' ? $code : '-1',
                'data'   => $data
            ];
        }

        return $result;
    }
}

/**
 * 通过信息提示也再跳转
 */
if (!function_exists('showMsg')) {
    function showMsg($url, $msg = '操作成功') {
        return redirect()->route('showMsg', ['url' => $url, 'msg' => $msg]);
    }
}

/**
 * 格式化时间 转为 多少前
 */
if (!function_exists('timeFormat')) {
    function timeFormat($time){
        $t = time()-$time;
        $f = [
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        ];

        foreach ($f as $k=>$v) {
            $c = floor($t/(int)$k);
            if (0 != $c) {
                return $c.$v.'前';
            }
        }

        return '刚刚';
    }
}

/**
 * 会员规则格式化时间 天抓换成月 年
 */
if (!function_exists('daysFormat')) {
    function daysFormat($days){
        $m = $days/30;
        if ($m < 12) {
            return $m.'个月';
        }
        if ($m >= 12) {
            $y = intval($m/12);
            $m1 = $m%12;
            if ($m1) {
                return $y.'年'.$m1.'个月';
            }else{
                return $y.'年';
            }
        }
    }
}

/**
 * 转化时间 将秒转为时分
 */
if (!function_exists('numberToTime')) {
    function numberToTime($number) {
        if (! $number) {
            return '0分钟';
        }

        $newTime = '';
        if (floor($number/3600) > 0) {
            $newTime .= floor($number/3600).'小时';
            $number = $number%3600;
        }
        if ($number/60 > 0) {
            $newTime .= ceil($number/60).'分钟';
        }

        return $newTime;
    }
}

/**
 * 简单合并对象元素
 */
if (!function_exists('objMerge')) {
    function objMerge($arrayOne, $arrayTwo)
    {
        if (count($arrayOne) == 0) {
            return $arrayTwo;
        }
        if (count($arrayTwo) == 0) {
            return $arrayOne;
        }
        $newArray = [];
        foreach ($arrayOne as $v) {
            $newArray[] = $v;
        }
        foreach ($arrayTwo as $v) {
            $newArray[] = $v;
        }

        return $newArray;
    }
}

/**
 * 随机生成干扰码
 * @param  integer $length [description]
 * @return [type]          [description]
 */
if (!function_exists('getSalt')) {
    function getSalt($length = 4)
    {
        $arr = array(
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '0',
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z'
        );
        $salt = '';
        for ($i = 0; $i < $length; $i++)
        {
            $salt .= $arr[rand(0, 61)];
        }
        return $salt;
    }
}
/**
 * 前台导航的选中状态
 * @param  $controller 控制器
 * @return true,false
 */
if (!function_exists('frontNavChecked')) {
    function frontNavChecked($controller='')
    {
        if ($controller && Route::current()->getAction()['prefix']) {
            list($temp,$currentController) = explode('/',Route::current()->getAction()['prefix']);
            return $currentController == $controller ?true:false;
        }elseif (!$controller &&  NULL == Route::current()->getAction()['prefix']) {
            return true;
        }
        return false;
    }
}
/**
 * 后台左侧导航的选中状态
 * @param  strng/array $url 菜单url
 * @param  int $type 在含有子菜单标签标志是否未子菜单判断
 * @return string 是否选中标志
 */
if (!function_exists('currentChecked')) {
    function currentChecked($url,$type=0)
    {
        if ($url && !is_array($url) && !$type) {
            $tempUrl1 = explode('/', explode('//',$url)[1]);
            $tempUrl2 = explode('/', explode('//',Request::url())[1]);

            if (count($tempUrl1)>2 && count($tempUrl2)>2 && $tempUrl1[2]==$tempUrl2[2]) {
                return 'active';
            }elseif ($url == Request::url()) {
                return 'active';
            }else{
                return '';
            }
        }elseif ($url && is_array($url) && $type) {
            $num = 0;
            $tempUrl2 = explode('/', explode('//',Request::url())[1]);
            foreach ($url as $k => $v) {
                $currentUrl = \App\Models\Menu::getMenuUrl($v['id']);
                $tempUrl1 = explode('/', explode('//',$currentUrl)[1]);
                if ($currentUrl == Request::url() || (count($tempUrl1)>2 && count($tempUrl2)>2 && $tempUrl1[2]==$tempUrl2[2])) {
                    $num++;
                }
            }
            if ($num) {
                return 'active';
            }else{
                return '';
            }
        }elseif ($url && !is_array($url) && $type) {
            if ($url == Request::url()) {
                return 'active';
            }else{
                return '';
            }
        }{
            return '';
        }
    }
}

/**
 * 获取5位的短信验证码
 */
function phoneCode($length = 4)
{
    $phoneCode = '';
    for ($i = 0; $i < $length; $i++)
    {
        $phoneCode .= rand(0, 9);
    }

    return $phoneCode;
}

/**
 * 判断浏览器是否为ie67
 */
if (!function_exists('ie67')) {
    function ie67() {
        if ( ! isset($_SERVER['HTTP_USER_AGENT'])) return false;
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') || false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')) {
            return true;
        }
        return false;
    }
}
/**
 * 判断浏览器是否为ie678
 */
if (!function_exists('ie678')) {
    function ie678() {
        if ( ! isset($_SERVER['HTTP_USER_AGENT'])) return false;
        if(false !== strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0') || false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0') || false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')) {
            return true;
        }
        return false;
    }
}

/**
 * 判定字符串是否在数组中 
 */
if (!function_exists('strInArray')) {
    function strInArray($string, $array){
        if ( ! $string) return false;
        if ( ! $array) return false;
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $v) {
                if ($string == $v) return true;

                $index  = strpos($v, '%');
                $strLen = strlen($v)-1;
                $strV   = str_replace('%', '', $v);
                if ($string == $strV) return true;

                if ($index === false) continue;

                $currIndex  = strpos($string, $strV);
                $currStrLen = strlen($string);
                if ($currIndex === false) continue;

                if ($index == $strLen && $currIndex == 0) {
                    return true;
                }

                if ($index == 0 && $currIndex == $currStrLen-$strLen) {
                    return true;
                }
            }
        }
        if (is_string($array)) {
            if ($string == $array) return true;

            $index  = strpos($array, '%');
            $strLen = strlen($array)-1;
            $strV   = str_replace('%', '', $array);
            if ($string == $strV) return true;

            if ($index === false) return false;

            $currIndex  = strpos($string, $strV);
            $currStrLen = strlen($string);
            if ($currIndex === false) return false;

            if ($index == $strLen && $currIndex == 0) {
                return true;
            }

            if ($index == 0 && $currIndex == $currStrLen-$strLen) {
                return true;
            }
        }

        return false;
    }
}

/**
 * [base64Encode 加密]
 */
if (!function_exists('base64Encode')) {
    function base64Encode($data) { 
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    }
}

/**
 * [base64Decode 解密]
 */
if (!function_exists('base64Decode')) {
    function base64Decode($data) { 
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    } 
}
/**
 * courseDuration 课时时间转换，秒数转换成**小时**分钟
 * @param  int $time 课时时长秒
 * @return string    格式化后的课程时长
 */
if (!function_exists('durationFormat')) {
    function durationFormat($time)
    {
        $value = [];
        $str   = '';
        if($time >= 3600){
          $value["hours"] = floor($time/3600);
          $time = ($time%3600);
          $str .= $value["hours"].'小时';
        }
        if($time >= 60){
          $value["minutes"] = floor($time/60);
          $time = ($time%60);
          $str .= $value["minutes"].'分钟';
        }

        return $str;
    }
}

if (!function_exists('getSessionId')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    function getSessionId()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->getId();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

/**
 * dataFormat        课时时间转换，秒数转换成**分钟:秒
 * @param  int $time 课时时长秒
 * @return string    格式化后的课程时长
 */
if (!function_exists('dataFormat')) {
    function dataFormat($time)
    {
        $minute = floor($time/60);
        if(strlen($minute) == 1 ){
            $minute = '0'.$minute;
        }
        $second = floor(($time - 60*$minute)%60);
        if(strlen($second) == 1 ){
            $second = '0'.$second;
        }
        $str = $minute.':'.$second;
        return $str;
    }
}
/**
 * [currentTime 格式化的当前时间]
 * @author wwj
 * @datetime 2016-01-12
 * @return   [timestamp]     [当前时间]
 */
if (!function_exists('currentTime')) {
    function currentTime($format = 'Y-m-d H:i:s')
    {
        return date($format,time());
    }
}
/**
 * 调试打印数据.
 *
 * @param  mixed
 * @return void
 */
if (!function_exists('vd')) {
    function vd()
    {
        header("Content-type: text/html; charset=utf-8");
        array_map(function ($x) { (new Dumper)->dump($x); }, func_get_args());
    }
}
/**
 * 数据过滤，删除重复的数据
 * @param  array $arr 待处理数据
 * @return array 处理后数组
 */
if (!function_exists('filterArray')) {
    function filterArray($arr)
    {
        $tempArr = [];
        foreach ($arr as $v) {
            if (!in_array($v,$tempArr)) {
                $tempArr[] = $v;
            }
        }
        return $tempArr;
    }
}

/**
 * 获取ip地址
 */
if ( ! function_exists('getClientIp')) {
    function getClientIp() {
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (!empty($_SERVER["REMOTE_ADDR"]))
            $ip = $_SERVER["REMOTE_ADDR"];
        else
            $ip = "error";
        return $ip;
    }
}

/**
 * 获取标题后缀名称
 */
if ( ! function_exists('getSuffixTitle')) {
    function getSuffixTitle() {

        return " - 火星网校";
    }
}

/**
 * 获取标题后缀名称
 */
if ( ! function_exists('getSiteUrl')) {
    function getSiteUrl($path = '') {
        $siteUrl = isset($_SERVER['HTTP_HOST']) ? 'http://'.$_SERVER['HTTP_HOST'] : 'http://www.vhxsd.cn';
        if ($path) {
            if (stripos($path, '/') !== 0)
                $path = '/'.$path;

            $siteUrl .= $path;
        }

        return $siteUrl;
    }
}
if (! function_exists('stringCut')) {
    /**
     * [stringCut 字符串截取，过长的截取一下]
     * @param  string $str [待操作字符串]
     * @param  string $length 截取长度
     * @return [string]      [截取后字符串]
     */
    function stringCut($str = '',$length = 30)
    {
        //字符串截取开始
        $start = 0;
        if ($str) {
            return mb_strlen($str,'UTF-8')>$length?mb_substr($str,$start,$length,'UTF-8').'…':$str;
        }else{
            return '';
        }
    }
}

/**
 * 检查缓存是否是第三方
 */
if (! function_exists('checkThirdCache')) {
    function checkThirdCache() {
        if ('ThirdMemcached' == config('cache.default')) {
            return true;
        }

        return false;
    }
}
/**
 * [vhxsdUrl 处理通行证，使用网校的网址]
 * @author wwj
 * @datetime 2016-01-07
 * @param    string     $url [网址路由]
 * @return   [type]          [网址]
 */
if (!function_exists('vhxsdUrl')) {
    function vhxsdUrl($url = '')
    {
        $realmName = config('sso.default.req_url');
        $url = ltrim($url,'/');
        $realmName = ltrim($realmName,'/');
        return $realmName.'/'.$url;
    }
}

/**
 * 给url链接上拼接参数
 */
if ( ! function_exists('urlAddParams')) {
    function urlAddParams($url, $params = []) {
        $url = urldecode(rtrim($url));
        $url = rtrim($url, '&?');
        if (stripos($url, '?') !== false) {
            $url = $url.'&';
        } else {
            $url .= '?';
        }

        if ($params) {
            foreach ($params as $key => $value) {
                $url .= $key.'='.$value.'&';
            }
        }

        return rtrim($url, '&');
    }
}
/**
 * [trimArray 处理数组，将所有的值trim去除空格]
 * @author wwj
 * @datetime 2016-01-14
 * @param    [type]     $array [待处理的数组]
 * @return   [type]            [description]
 */
if (!function_exists('trimArray')) {
    function trimArray($array)
    {
        foreach ($array as &$v) {
            if (is_array($v)) {
                $v = trimArray($v);
            }else{
                $v = trim($v);
            }
        }
        return $array;
    }
}
/**
 * [filterEmptyArray 过滤数组，将值为空的记录unset]
 * @author wwj
 * @datetime 2016-09-23
 * @param    [type]     $array [待处理的数组]
 * @return   [type]            [description]
 */
if (!function_exists('filterEmptyArray')) {

    function filterEmptyArray($array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $array[$k] = filterEmptyArray($v);
            }else{
                if (!$v) {
                    unset($array[$k]);
                }
            }
        }

        return $array;
    }
}

/**
 * [返回json数据]
 * @author wwj
 * @datetime 2016-09-23
 * @param    [type]     $array [待处理的数组]
 * @return   [type]            [description]
 */
if (!function_exists('echoJsonData')) {

    function echoJsonData($array)
    {
        $data = '';
        if ($array)
        {
            $data = json_encode($array);
        }
        echo $data;
        exit;
    }
}

if (!function_exists('guid'))
{
/**
 * 生成GUID方法 
 * @return string
 */
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        // chr(123)// "{"
        // chr(125)// "}"
        $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}
}
/**
 * Prints out debug information about given variable.
 *
 * @param string $var Variable to show debug information for.
 * @param boolean $exit If set to true, exit.
 * @param boolean $showFrom If set to true, the method prints from where the function was called.
 * @param boolean $showHtml If set to true, the method prints the debug data in a screen-friendly way.
 */
if (!function_exists('debuger'))
{
    function debuger($var = '', $exit = false, $showFrom = true, $showHtml = false)
    {
        if ($showFrom)
        {
            $calledFrom = debug_backtrace();
            echo '<strong>' . $calledFrom[0]['file'] . '</strong>';
            echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
        }
        echo "\n<pre>\n";

        $var = print_r($var, true);
        if ($showHtml)
        {
            $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
        }
        echo $var . "\n</pre>\n";

        if ($exit)
        {
            exit();
        }
    }
}
if (!function_exists('x'))
{
    function x($var = '')
    {
        $calledFrom = debug_backtrace();
        echo '<strong>' . $calledFrom[0]['file'] . '</strong>';
        echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
        echo "\n<pre>\n";
        $var = print_r($var, true);
        echo $var . "\n</pre>\n";
        exit();
    }
}

/**
 * 将数组按某一键重组
 * @param $field  字段
 * @param $data   数据
 * @param $value  值
 */
if (!function_exists('fieldByData'))
{
    function fieldByData ($field, $data = [], $value = '')
    {
        if (empty($field) || empty($data))
        {
            return [];
        }
        $result = [];
        foreach ($data as $val)
        {
            if ($value)
            {
                $result[$val[$field]] = isset($val[$value]) ? $val[$value] : '';
            }
            else 
            {
                $result[$val[$field]] = $val;
            }
        }
        return $result;
    }
}

/**
 * 将数组按某一键重组为二维数组
 * @param $field  字段
 * @param $data   数据
 * @param $value  值
 */
if (!function_exists('fieldToArrayByData'))
{
    function fieldToArrayByData ($field, $data = [], $value = '')
    {
        if (empty($field) || empty($data))
        {
            return [];
        }
        $result = [];
        foreach ($data as $val)
        {
            if ($value)
            {
                $result[$val[$field]][] = isset($val[$value]) ? $val[$value] : '';
            }
            else 
            {
                $result[$val[$field]][] = $val;
            }
        }
        return $result;
    }
}
/**
 * 返回数组中指定多列
 *
 * @param  Array  $input       需要取出数组列的多维数组
 * @param  String $column_keys 要取出的列名，逗号分隔，如不传则返回所有列
 * @param  String $index_key   作为返回数组的索引的列
 * @return Array
 */
if (!function_exists('fieldToArrayColumns'))
{
    function fieldToArrayColumns($input, $column_keys=null, $index_key=null){
        $result = array();
        $keys =isset($column_keys)? explode(',', $column_keys) : array();
        if($input){
            foreach($input as $k=>$v){
                // 指定返回列
                if($keys){
                    $tmp = array();
                    foreach($keys as $key){
                        $tmp[$key] = $v[$key];
                    }
                }else{
                    $tmp = $v;
                }
                // 指定索引列
                if(isset($index_key)){
                    $result[$v[$index_key]] = $tmp;
                }else{
                    $result[] = $tmp;
                }
            }
        }
        return $result;
    }
}


if (! function_exists('filterArrayEmptyItem'))
{
    /**
     * 过滤数组空元素
     * @param type $arr
     * @param type $newArr
     * @return type
     */
    function filterArrayEmptyItem (&$arr)
    {
        if (! $arr) return [];
        foreach ($arr as $key => &$val)
        {
            if (is_array($val))
            {
                filterArrayEmptyItem($val);
            }   
            else if (!$val)
            {
                unset($arr[$key]);
            }
        }
    }
}

/**
 * 将数组每个元素增加另一个数组的指定的值
 * @param $field  字段
 * @param $data   数据
 * @param $value  值
 */
if (!function_exists('unionTwoArrayByKey'))
{
    function unionTwoArrayByKey (& $first = [], $second = [], $columns = [], $field = '')
    {
        if (empty($first) || empty($second))
        {
            return [];
        }
        foreach ($second as $key => $val)
        {
            if (array_key_exists($field, $val))
            {
                foreach ($val as $k => $v)
                {
                    if (in_array($k, $columns))
                    {
                        $first[$key][$k] = $v;
                    }
                }
            }
        }

        return $first;
    }
}

/**
 * 转换数组
 */
if (!function_exists('convertToArray'))
{
    function convertToArray($key = '')
    {
        return $key ? (is_array($key) ? $key : [$key]) : [];
    }
}

if (!function_exists('dayDiff'))
{
    /**
    * 计算2个日期之间相差的天数
    *
    * @param string $date1 yyyy-mm-dd, mm/dd/yyyy
    * @param string $date2 yyyy-mm-dd, mm/dd/yyyy
    * @return integer
    */
   function dayDiff($date1, $date2)
   {
       $t1 = strtotime($date1);
       $t2 = strtotime($date2);

       if ($t1 > $t2)
       {
           $t = $t2;
           $t2 = $t1;
           $t1 = $t;
       }

       return round(($t2 - $t1) / 3600 / 24);
   }
}

if (!function_exists('weeksBetweenDays'))
{
    /**
     * 计算2个日期之间星期数
     *
     * @param string $date1 yyyy-mm-dd, mm/dd/yyyy
     * @param string $date2 yyyy-mm-dd, mm/dd/yyyy
     *
     * @return integer
     */
    function weeksBetweenDays($date1, $date2)
    {
        $days = dayDiff($date1, $date2);
        return ceil($days / 7);
    }
}

if (!function_exists('diffToMinute'))
{
    /**
     * 把2个时间差（2个unix_timestamp相减）转换为小时与分数
     *
     * @param integer $diff
     *
     * @return string
     */
    function diffToMinute($diff)
    {
        $minute = round(($diff % 3600) / 60);
        return round($diff / 3600) . '时' . $minute . '分';
    }
}

if (!function_exists('minuteToHour'))
{
    /**
     * 将分钟转换为小时分钟显示
     *
     * @param integer $minute
     *
     * @return string
     */
    function minuteToHour($minute)
    {
        $minute = (int) $minute;
        if (0 == $minute)
        {
            return '-';
        }
        else if ($minute < 60)
        {
            return $minute . '分';
        }
        else
        {
            return floor($minute / 60) . '时' . ($minute % 60) . '分';
        }
    }
}

if (!function_exists('getTodayStartTime'))
{
    /**
     * 获取今天开始时间的时间戳
     *
     * @return integer
     */
    function getTodayStartTime()
    {
        return strtotime(date('Y-m-d') . ' 00:00:00');
    }
}

if (!function_exists('getBeginAndEndTime'))
{
    /**
     * 获取某天的开始结束时间戳
     *
     * @param string $day
     * @param integer $begin
     * @param integer $end
     */
    function getBeginAndEndTime($day, & $begin, & $end)
    {
        $begin = getUtCtime($day);
        $end = getUTCtime($day . ' 23:59:59');
    }
}

if (!function_exists('getTodayEndTime'))
{
    /**
     * 获取今天结束时间的时间戳
     *
     * @return integer
     */
    function getTodayEndTime()
    {
        return strtotime(date('Y-m-d') . ' 23:59:59');
    }
}

if (!function_exists('plusDaysToDay'))
{
    /**
     * 给某天(Y-m-d)加上或减去几天，$ignoreWeekend表示是否忽略周末
     * 比如：2010-03-15(周一)，往前移动一天时，如果$ignoreWeekend为true，则返回2010-03-12；否则返回2010-03-14
     *
     * @param string $day
     * @param integer $days
     * @param boolean $ignoreWeekend
     *
     * @return string Y-m-d
     */
    function plusDaysToDay($day, $days = 0, $ignoreWeekend = true)
    {
        if (0 == $days)
        {
            return $day;
        }

        // 改变后的日期
        $newDay = date('Y-m-d', strtotime("+{$days} day", strtotime($day)));

        if ($ignoreWeekend)
        {
            $week = getDayWeek($newDay);
            // 周日
            if (0 == $week)
            {
                // 往后
                if ($days > 0)
                {
                    return plusDaysToDay($newDay, 1, $ignoreWeekend);
                }
                else
                {
                    return plusDaysToDay($newDay, -2, $ignoreWeekend);
                }
            }
            else if (6 == $week)
            {
                // 往后
                if ($days > 0)
                {
                    return plusDaysToDay($newDay, 2, $ignoreWeekend);
                }
                else
                {
                    return plusDaysToDay($newDay, -1, $ignoreWeekend);
                }
            }
            else
            {
                return $newDay;
            }
        }
        else
        {
            return $newDay;
        }
    }
}

if (!function_exists('getDayWeek'))
{
    /**
     * 获取某天是星期几
     *
     * @param string $day
     *
     * @return string
     */
    function getDayWeek($day)
    {
        return date('w', strtotime($day));
    }
}

if (!function_exists('getMonthDays'))
{
    /**
     * 获取某个月份有多少天，比如2010年3月返回31
     *
     * @param string $month 201003
     *
     * return integer
     */
    function getMonthDays($month)
    {
        return date('t', strtotime("{$month}01"));
    }
}

if (!function_exists('ObjectToArray'))
{
    function ObjectToArray($obj)
    {
        return $obj = is_object($obj) ? get_object_vars($obj) : $obj;
    }
}

if (!function_exists('validate_setting_value'))
{
    /**
    * Validates the provided value of a setting against its datatype
    *
    * @param	mixed	(ref) Setting value
    * @param	string	Setting datatype ('number', 'boolean' or other)
    * @param	boolean	Represent boolean with 1/0 instead of true/false
    * @param boolean  Query database for username type
    *
    * @return	mixed	Setting value
    */
    function validate_setting_value(&$value, $datatype, $bool_as_int = true, $username_query = true)
    {
        switch ($datatype)
        {
            case 'number':
                $value += 0;
                break;

            case 'boolean':
                $value = ($bool_as_int ? ($value ? 1 : 0) : ($value ? true : false));
                break;

            case 'bitfield':
                if (is_array($value))
                {
                    $bitfield = 0;
                    foreach ($value AS $bitval)
                    {
                        $bitfield += $bitval;
                    }
                    $value = $bitfield;
                }
                else
                {
                    $value += 0;
                }
                break;
            default:
                $value = trim($value);
        }

        return $value;
    }
}

if (!function_exists('getArrayByKey'))
{
    /**
     * 根据键获取数组某一个元素
     * @param type $key
     * @param type $arr
     * @return type
     */
    function getArrayByKey ($key, $arr)
    {
        $ak = [];
        if (array_key_exists($key, $arr))
        {
            foreach ($arr as $k => $v)
            {
                if ($k == $key)
                {
                    $ak[$key] = $arr[$key];
                }
            }
        }
        return $ak;
    }
}

if (!function_exists('formDate'))
{
    /**
     * 时间格式转换
     * @param type $time
     * @param type $form
     * @return type
     */
    function formDate($time = 0, $form = 'Y-m-d H:i:s')
    {
        $time = $time ? $time : time();
        return date($form, $time);
    }
}

if (!function_exists('getMonthStartEndTime'))
{
    /**
     * 获取月份的第一天和最后一天时间戳
     * @param type $month
     * @param type $monthStart
     * @param type $monthEnd
     */
    function getMonthStartEndTime (& $month = '',& $monthStart,& $monthEnd)
    {
        $month = $month ? $month : date('Y-m');
        //本月开始时间
        $monthStart = date($month . '-01') . " 00:00:00";
        //本月结束时间
        $monthEnd = date('Y-m-d 23:59:59', strtotime("{$monthStart} +1 month -1 day"));
        $monthStart = getUTCtime($monthStart);
        $monthEnd = getUTCtime($monthEnd);
    }
}

if (!function_exists('getUTCtime'))
{
    /**
     * 获取UTC时间
     * @param type $date
     * @return int
     */
    function getUTCtime ($date)
    {
        if (empty($date))
        {
            return 0;
        }
//        $miscParam = MiscparamRepository::getInstance();
        return strtotime($date);
    }
}

if (!function_exists('arrayAddColumn'))
{
    /**
     * 数组添加元素
     * @param type $arr
     * @param type $column
     * @return type
     */
    function arrayAddColumn (&$arr, $column = [])
    {
        if (!is_array($arr) || !is_array($column)) return $arr;
        foreach ($arr as $k => &$v)
        {
           $v += $column;
        }
        return $arr;
    }
}

if (!function_exists('getTheWeek'))
{
    /**
     * 获取本周开始结束日期
     * @param type $weekStart
     * @param type $weekEnd
     */
    function getTheWeek (& $weekStart, & $weekEnd)
    {
        $defaultDate = date('Y-m-d');
        $first = 1;
        $w = date('w', strtotime($defaultDate));
        $weekStart = date('Y-m-d', strtotime("$defaultDate -" . ($w ? $w - $first : 6) . ' days'));
        $weekEnd = date('Y-m-d', strtotime("$weekStart + 6 days"));
    }
}

if (!function_exists('getLastWeek'))
{
    /**
     * 获取上周开始结束日期
     * @param type $lastStart
     * @param type $lastEnd
     */
    function getLastWeek (& $lastStart, & $lastEnd)
    {
        $defaultDate = date('Y-m-d');
        $first = 1;
        $w = date('w', strtotime($defaultDate));
        $weekStart = date('Y-m-d', strtotime("$defaultDate -" . ($w ? $w - $first : 6) . ' days'));
        $weekEnd = date('Y-m-d', strtotime("$weekStart + 6 days"));
        $lastStart = date('Y-m-d',strtotime("$weekStart - 7 days"));
        $lastEnd = date('Y-m-d',strtotime("$weekStart - 1 days"));
    }
}

if (! function_exists('arrayToSlice'))
{
    /**
     * 数组分组
     * @param type $array
     * @param type $flag
     * @return type
     */
    function arrayToSlice($array = [], $flag = 1)
    {
        $result = [];
        $num = count($array) / $flag;
        for ($i = 0; $i <= $num; $i ++)
        {
            $result[$i] = array_slice($array, $i * $flag, $flag);
        }
        return $result;
    }
}

if (! function_exists('convertDictionary'))
{
    /**
     * 转换字典
     * @param type $array
     * @param type $columns
     * @return type
     */
    function convertDictionary($array = [], $columns = ['id', 'title'])
    {
        $result = [];
        list($a, $b) = $columns;
        foreach($array as $key => $val){
            $result[] = [$a => $key, $b => $val];
        }
        return $result;
    }
}

if (! function_exists('sortIntentionflow'))
{
    /**
     * 字典排序
     * @param type $array
     * @return type
     */
    function sortIntentionflow($array = [], $flag = true)
    {
        $data = [];
        if($array){
            if($flag){
                foreach($array as $k=>$v){
                    if($k <= 100){
                        $data[0][$k] = $v;
                    }else{
                        $key = substr($k, 0, 1);
                        $data[$key][$k] = $v;
                        ksort($data[$key]);
                    }            
                } 
            }else{
                foreach($array as $k=>$v){
                    if($k > 100){
                        $data[$k] = $v;
                    }
                }
                ksort($data);
            }
            
        }
        unset($array, $flag);
        return $data;
    }
}

if (! function_exists('arrayaddkey'))
{
    function arrayaddkey (& $val, $key, $param)
    {
        $val[$param['key']] = $param['val'];
    }
}

if (! function_exists('doRequestCurl'))
{
    function doRequestCurl($url, $data, $requestType = 'post')
    {
        if ('get' == $requestType){
            //get请求则把参数拼接在 $url 后面
            $url = $url . '?' . http_build_query($data);
        }else{
            $url = $url;
        }
        $curl = curl_init($url);
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置执行时间10秒钟
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);  
        //设置获取的信息以文件流的形式返回，而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ('post' == strtolower($requestType)){
            curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        //执行命令
        $status = curl_exec($curl);
        curl_close($curl);
        return $status;
    }
}

if (! function_exists('iif'))
{
    /**
    * Essentially a wrapper for the ternary operator.
    *
    * @deprecated	Deprecated. Use the ternary operator.
    *
    * @param	mixed	Expression to be evaluated
    * @param	mixed	Return this if the expression evaluates to true
    * @param	mixed	Return this if the expression evaluates to false
    *
    * @return	mixed	Either the second or third parameter of this function
    */
   function iif($expression, $returntrue, $returnfalse = '')
   {
       return (!empty($expression) ? $returntrue : $returnfalse);
   }   
}

if (! function_exists('convertType'))
{
    /**
     * 转换类型
     * @param type $param
     * @param type $type
     * @return type
     */
   function convertType(& $mixed, $type = 1)
   {
      if ($mixed){
          switch($type){
                case 1:$mixed = intval($mixed);
                    break;
                case 2:$mixed = (string) $mixed;
                    break;
                case 3:$mixed = (array) $mixed;
                    break;
                case 4:$mixed = (object) $mixed;
                    break;
            }
        }
        return $mixed;
   }   
}

if (! function_exists('utf8_strrev'))
{
   /**
    * 反转utf8的字符串，使用正则和数组实现
    * @param string $str
    * @return string
    */
   function utf8_strrev($str)
   {
       preg_match_all('/./us', $str, $ar);
       return implode('', array_reverse($ar[0]));               
   }   
}

if (! function_exists('sliceArray'))
{
   /**
    * 截取数组
    * @param string $str
    * @return string
    */
   function sliceArray($array, $keys = [])
   {
       $newArray = [];
       if($array && $keys){
           foreach($array as $k => $v){
               if(in_array($k, $keys)){
                   $newArray[$k] = $v;
               }
           }
       }
       return $newArray;
   }   
}

if (! function_exists('getDateFromRange'))
{
    /**
     * 获取指定日期段内每天的日期
     * @param type $startdate
     * @param type $enddate
     * @return type
     */
    function getDateFromRange($startdate, $enddate)
    {
        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);

        // 计算日期段内有多少天
        $days = ($etimestamp-$stimestamp)/86400+1;

        // 保存每天日期
        $date = array();

        for($i=0; $i<$days; $i++){
            $date[] = date('Y-m-d', $stimestamp+(86400*$i));
        }

        return $date;
    }
}

if(!function_exists('filterEmoji'))
{
    /**
     * 过滤掉emoji表情
     * @param type $str
     * @return type
     */
    function filterEmoji($str)
    {
        $regex = '/(\\\u[ed][0-9a-f]{3})/i';  
        $str = json_encode($str);  
        $str = preg_replace($regex, '', $str);  
        return json_decode($str);  
    }
}

if(!function_exists('getWeekByYear'))
{
    /**
     * 获取某一年的周
     * @param type $str
     * @return type
     */
    function getWeekByYear ($y = '')
    {
        $y = intval($y);
        $starttime = getUTCTime($y.'-01-01');
        $w = date('w', $starttime);
        $w = $w == 0 ? 7 : $w;
        $weeks = [];
        //获取年第一周的日期
        if (1 != $w){
            $starttime = $starttime + ((7 - $w + 1) * 86400);
        }
        $j = 1;
        for ($i = $starttime; (int)date('Y', $i) <= $y; $i += (7 * 86400)){
            $s = date('Y-m-d', $i);
            $e = date('Y-m-d', $i + (6 * 86400));
            $weeks[$j] = [
                str_replace("-", ".", $s ),
                str_replace("-", ".", $e)
            ];
            $j ++;
        }
        return $weeks;
    }
}

if(!function_exists('phraseTrans'))
{
    function phraseTrans ($phrase = '', $prex = 'prompt')
    {
        $prex = $prex ? $prex : '';
        return trans($prex . '.' . $phrase);
    }
}

if(!function_exists('roundRate'))
{
    function roundRate($a, $b, $prec = 2, $type = '%')
    {
        $default = 0 . $type;
        return $b ? round($a / $b, $prec) * 100 . $type  : $default;
    }
}

/**\
 * 二维数组排序
 * $sorted = array_orderby($data, $key1, $sort1, $key2, $sort2);
 * $data 要排序的数据
 * $key1 第一规则字段名
 * $sort1 第一排序规则(SORT_DESC  /   SORT_ASC)
 * $key2 第二规则字段名
 * $sort2 第二排序规则
 * @return mixed
 */
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

if(!function_exists('is_utf8'))
{
    /**
     * 字符串是否为utf8
     * @param type $str
     * @return type
     */
    function is_utf8($str)
    {
        $len = strlen($str);
        for($i = 0; $i < $len; $i++){
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c > 247)) return false;
                elseif ($c > 239) $bytes = 4;
                elseif ($c > 223) $bytes = 3;
                elseif ($c > 191) $bytes = 2;
                else return false;
                if (($i + $bytes) > $len) return false;
                while ($bytes > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bytes--;
                }
            }
        }
        return true;
    }
}

if(!function_exists('getWeek'))
{
    /**
     * 获取时间段所在周
     * @param type $startdate
     * @param type $enddate
     * @return type
     */
    function getWeek($startdate,$enddate) 
    { 
        //参数不能为空 
        $arr = [];
        if(empty($startdate) || empty($enddate)){ 
            return $arr;   
        }
        //先把两个日期转为时间戳 
        $startdate=strtotime($startdate); 
        $enddate=strtotime($enddate); 
        //开始日期不能大于结束日期 
        if($startdate<=$enddate){ 
            $end_date=strtotime("next monday",$enddate); 
            if(date("w",$startdate)==1){ 
                $start_date=$startdate; 
            }else{ 
                $start_date=strtotime("last monday",$startdate); 
            } 
            //计算时间差多少周 
            $countweek=($end_date-$start_date)/(7*24*3600); 
            for($i=0;$i<$countweek;$i++){ 
                $sd=date("Y-m-d",$start_date); 
                $ed=strtotime("+ 6 days",$start_date); 
                $eed=date("Y-m-d",$ed); 
                $arr[]=array($sd,$eed); 
                $start_date=strtotime("+ 1 day",$ed); 
            }    
        } 
        return $arr;   
    } 
}

if(!function_exists('getWeekRangeDay'))
{
    /**
     * 将时间段转换周对应日期
     * @param type $startdate
     * @param type $enddate
     * @return type
     */
    function getWeekRangeDay($startdate,$enddate) 
    { 
        //参数不能为空 
        $arr = [];
        if(empty($startdate) || empty($enddate)){ 
            return $arr;   
        }
        //先把两个日期转为时间戳 
        $startdate=strtotime($startdate); 
        $enddate=strtotime($enddate); 
        //开始日期不能大于结束日期 
        if($startdate<=$enddate){ 
            $end_date=strtotime("next monday",$enddate); 
            if(date("w",$startdate)==1){ 
                $start_date=$startdate; 
            }else{ 
                $start_date=strtotime("last monday",$startdate); 
            } 
            //计算时间差多少周 
            $countweek=($end_date-$start_date)/(7*24*3600); 
            for($i=0;$i<$countweek;$i++){ 
                $sd=date("Y-m-d",$start_date); 
                $ed=strtotime("+ 6 days",$start_date); 
                $eed=date("Y-m-d",$ed); 
                $arr[]= getDateFromRange($sd,$eed); 
                $start_date=strtotime("+ 1 day",$ed); 
            }  
        } 
        return $arr;   
    } 
}

if(!function_exists('getWeekStartEndByDay'))
{
    /**
     * 获取日期所在周的第一天和最后一天
     * @param type $startdate
     * @param type $enddate
     * @return type
     */
    function getWeekStartEndByDay($day) 
    { 
        //参数不能为空 
        $date = [];
        if(empty($day)){ 
            return $date;   
        }
        $lastDay = date("Y-m-d",strtotime("$day Sunday"));
        $firstDay = date("Y-m-d",strtotime("$lastDay - 6 days"));
        $date['startdate'] = $firstDay;
        $date['enddate'] = $lastDay;
        return $date;   
    } 
}

if(! function_exists('getYearMonthByDay'))
{
    /**
     * 获取时间段范围的年月份
     * @param type $startdate
     * @param type $enddate
     * @return type
     */
    function getYearMonthByDay ($startdate, $enddate){
        $month = [];
        if (!$startdate || ! $enddate){
            return [];
        }
        $starttime = strtotime($startdate); 
        $endtime = strtotime($enddate);
        $num = ($endtime - $starttime)/ (24*3600);
        for($i = 0; $i <= $num; $i ++){
            $m = date('Y-m', $starttime);
            $month[$m] = $m;
            $starttime = $starttime + (24*3600);
        }
        return $month;
    }
}
if(!function_exists('curlRequest'))
{
    /**
     * Curl 请求封装函数(支持GET和POST方式)
     * 
     * @param string  $url     请求的url
     * @param array   $params  请求传递的参数
     * @param string  $method  请求方式:GET/POST,默认GET
     * @param array   $header  请求要携带的header头
     * @param integer $timeout 请求超时时间
     * @return array
     */
    function curlRequest($url, $params = [], $method = 'GET', $header = [], $timeout = 600) 
    {
        $res = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if(!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true); //当需要通过curl_getinfo来获取发出请求的header信息时,该选项需要设置为true
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case 'GET':
                if (!empty($params)) {
                    $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($params) ? http_build_query($params) : $params);
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); 
                break;
            case 'POST':
                if(class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else if(defined('CURLOPT_SAFE_UPLOAD')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                break;
        }
        $res['url'] = $url;
        $beginTime  = microtime(true);
        $res['tmpInfo']  = curl_exec($ch);
        $res['execTime'] = microtime(true) - $beginTime;
        if(curl_errno($ch)) { //curl报错
            $res['error_code'] = curl_errno($ch);
            $res['error_msg']  = curl_error($ch);
        } else {
            $res['getInfo'] = curl_getinfo($ch);
        }
        curl_close($ch); //关闭会话
        return $res;
    }
}


if(!function_exists('getWeekByTerm'))
{
    /**
     * 获取期区间的周
     * @param type $str
     * @return type
     */
    function getWeekByTerm ($termmonth = [],$startTermid = 0, $endTermid = 0)
    {
        $startTermid = intval($startTermid);
        $endTermid = intval($endTermid);
        $termWeek = [];
        for($i = $startTermid; $i <= $endTermid; $i ++)
        {
            if(isset($termmonth[$i]))
            {
                $starttime = strtotime($termmonth[$i]['startdate']);
                $termWeek[$i] = $termmonth[$i];
                for($j = 0; $j < 4; $j ++)
                {
                    if(0 == $j)
                    {
                        $termWeek[$i]['week'][$j] = $starttime;
                        $termWeek[$i]['weekday'][$j] = date('Y-m-d',$starttime);
                    }
                    else
                    {
                        $termWeek[$i]['week'][$j] = $starttime + $j * 86400 * 7;
                        $termWeek[$i]['weekday'][$j] = date('Y-m-d',$starttime + $j * 86400 * 7);
                    }
                }

            }
        }
        return $termWeek;
    }
}

if(!function_exists('resultToArray'))
{
    /**
    * 对象转数组
    * @param type $data
    * @return type Array
    */
   function resultToArray($data){
       $return = [];
       if($data){
           foreach($data as $key=>$val){
               $return[$key] = (array)$val;
           }
       }
       return $return;
   }
}

if(!function_exists('searchKey'))
{
    /**
     * 多维数组中查找指定键对应的值
     * 适合各种多维数组
     *
     * @param $data
     * @param $searchKey
     * @param $res
     */
    function searchKey($data, $searchKey, &$res){
        if(!is_array($data)) return;
        foreach($data as $key => $row){
            if(!is_array($row) && $key == $searchKey){
                $res[] = $row;
            }else if(is_array($row)){
                searchKey($row, $searchKey, $res);
            }
        }
    }
}

if(!function_exists('excelExportSort'))
{
    /**
     * excel数据导出，根据自定义列排序
     *
     * @param $data
     * @param $searchKey
     * @param $res
     */
    function excelExportSort($data, $demo, $need = false){
        $res = [];
        foreach ($data as $key => $val){
            array_walk($demo, function($q, $k)use(&$res, $key, $val){
                $res[$key][$k] = $val[$k] ?: '';
            });
        }
        if($need){
            array_unshift($res, $demo);
        }
        return $res;
    }
}

if(! function_exists('object_array'))
{
    //调用这个函数，将其幻化为数组，然后取出对应值
    function object_array($array)
    {
        if(is_object($array)){
            $array = (array)$array;
        }
        if(is_array($array)){
            foreach($array as $key=>$value){
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }
}

if(! function_exists('PublishSocket'))
{
    /*
     * @param
     * $to_uid // 指明给谁推送，为空表示向所有在线用户推送
     */
    function PublishSocket($content = [] , $to_uid = ''){
        if(empty($content)){
            return [];
        }
        // 指明给谁推送，为空表示向所有在线用户推送
        //$to_uid = "";
        // 推送的url地址，使用自己的服务器地址
        $push_api_url = Config('socket.newUrl').":2121/";
        $post_data = array(
            "type" => "publish",
            "content" => json_encode($content),
            "to" => $to_uid,
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        //var_export($return);
        return ['url' => $push_api_url,'return'=>$return];        
    }
}

if(! function_exists('combineArray')){

    /**
     *  组合数组元素
     * @param $data， 数据信息
     * @param $fieldConfig 组合字段配置
     * @return array
     */
    function combineArray($data, $fieldConfig, $default = '')
    {
        $rt = [];
        foreach($fieldConfig as  $fromField => $field){
            $rt[$field] = isset($data[$fromField]) ? $data[$fromField] : $default;
        }
        return $rt;
    }
}

if(! function_exists('getNextDate'))
{
    /**
     * PHP返回明天的日期
     * @param int $offset
     * @return false|string
     */
    function getNextDate($offset = 1) {
        $offset = $offset ? $offset : 1;
        $date = mktime(0,0,0,date("m"),date("d") + $offset,date("Y"));
        return date("Y-m-d", $date);
    }
}

if(! function_exists('_isset'))
{
    /**
     * 检测数组是否存在某个key,并且不为空
     * @param array $data
     * @param string $field
     * @param string $default
     * @return mixed|string
     */
    function _isset(array $data, string $field, $default = '')
    {
        if(! $data || ! $field) return '';

        return isset($data[$field]) && $data[$field] ? $data[$field] : $default;
    }
}

//if(! function_exists('Num_To_Rmb'))
//{
//    /**
//    *数字金额转换成中文大写金额的函数
//    *String Int $num 要转换的小写数字或小写字符串
//    *return 大写字母
//    *小数位为两位
//    **/
//    function Num_To_Rmb($num){
//        $c1 = "零壹贰叁肆伍陆柒捌玖";
//        $c2 = "分角元拾佰仟万拾佰仟亿";
//        //精确到分后面就不要了，所以只留两个小数位
//        $num = round($num, 2); 
//        //将数字转化为整数
//        $num = $num * 100;
//        if (strlen($num) > 10) {
//            return "金额太大，请检查";
//        } 
//        $i = 0;
//        $c = "";
//        while (1) {
//            if ($i == 0) {
//                //获取最后一位数字
//                $n = substr($num, strlen($num)-1, 1);
//            } else {
//                $n = $num % 10;
//            }
//            //每次将最后一位数字转化为中文
//            $p1 = substr($c1, 3 * $n, 3);
//            $p2 = substr($c2, 3 * $i, 3);
//            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
//                $c = $p1 . $p2 . $c;
//            } else {
//                $c = $p1 . $c;
//            }
//            $i = $i + 1;
//            //去掉数字最后一位了
//            $num = $num / 10;
//            $num = (int)$num;
//            //结束循环
//            if ($num == 0) {
//                break;
//            } 
//        }
//        $j = 0;
//        $slen = strlen($c);
//        while ($j < $slen) {
//            //utf8一个汉字相当3个字符
//            $m = substr($c, $j, 6);
//            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
//            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
//                $left = substr($c, 0, $j);
//                $right = substr($c, $j + 3);
//                $c = $left . $right;
//                $j = $j-3;
//                $slen = $slen-3;
//            } 
//            $j = $j + 3;
//        } 
//        //这个是为了去掉类似23.0中最后一个“零”字
//        if (substr($c, strlen($c)-3, 3) == '零') {
//            $c = substr($c, 0, strlen($c)-3);
//        }
//        //将处理的汉字加上“整”
//        if (empty($c)) {
//            return "零元整";
//        }else{
//            return $c . "整";
//        }
//    }
//
//}

if(! function_exists('Num_To_Rmb')) {

    function Num_To_Rmb($ns)
    {
        static $cnums = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"),
        $cnyunits = array("圆", "角", "分"),
        $grees = array("拾", "佰", "仟", "万", "拾", "佰", "仟", "亿");
        list($ns1, $ns2) = explode(".", $ns, 2);
        $ns2 = array_filter(array($ns2[1], $ns2[0]));
        $ret = array_merge($ns2, array(implode("", _cny_map_unit(str_split($ns1), $grees)), ""));
        $ret = implode("", array_reverse(_cny_map_unit($ret, $cnyunits)));
        return str_replace(array_keys($cnums), $cnums, $ret);
    }

    function _cny_map_unit($list, $units)
    {
        $ul = count($units);
        $xs = array();
        foreach (array_reverse($list) as $x) {
            $l = count($xs);
            if ($x != "0" || !($l % 4))
                $n = ($x == '0' ? '' : $x) . ($units[($l - 1) % $ul]);
            else
                $n = is_numeric($xs[0][0]) ? $x : '';
            array_unshift($xs, $n);
        }
        return $xs;
    }

}

if(! function_exists('get_weeks')) {
    /**
     * 获取指定日期之间的各个周
     */
    function get_weeks($sdate, $edate)
    {
        $range_arr = array();
        // 计算各个周的起始时间
        do {
            $idx = strftime("%u", strtotime($sdate));
            $mon_idx = $idx - 1;
            $sun_idx = $idx - 7;
            $weekinfo = [
                'week_start_day' => date('Y-m-d', strtotime($sdate) - $mon_idx * 86400),
                'week_end_day' => date('Y-m-d', strtotime($sdate) - $sun_idx * 86400),
            ];
            $end_day = $weekinfo['week_end_day'];
            $sd = date('Y-m-d', strtotime($weekinfo['week_start_day']));
            $ed = date('Y-m-d', strtotime($weekinfo['week_end_day']));
            $range['sd'] = $sd;
            $range['ed'] = $ed;
            $range_arr[] = $range;
            $sdate = date('Y-m-d', strtotime($sdate) + 7 * 86400);
        } while ($end_day <= $edate);
        return $range_arr;
    }
}

if(! function_exists('getTodayTime')) {
    function getTodayTime() {
        $date = date('Y-m-d');
        return strtotime($date);
    }
}

if(! function_exists('getNextTime')) {
    function getNextTime($offset = 1) {
        $offset = $offset ? $offset : 1;
        $date = mktime(0,0,0,date("m"),date("d") + $offset,date("Y"));
        return strtotime(date("Y-m-d", $date));
    }
}

if(!function_exists('DepartmentTree'))
{
    function DepartmentTree($data, $pId) {
        static $list = [];
        $fn = function(&$data,&$pId) use(&$fn, &$n, &$list){
            $tree = [];
            foreach($data as $k => $v) {
                if($v['department_parentid'] == $pId) {
                    $fn($data, $v['department_id'], $list);
                    $list[$v['department_id']] = $v;
                }
            }
            return $list;
        };
        return $fn($data,$pId, $list);
    }
}
