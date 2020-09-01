<?php
/**
 * Created by VIM.
 * Author:YQ
 * Date:2019/03/18 13:41:50
 * 基于Cache的分布式悲观锁实现
 */
namespace App\Tools;
use Exception;
use Cache;
/**
 * DistributedLocker
 * 基于cache实现的悲观锁
 * @package App\Tools
 * @date 2019-03-18
 * @author YQ
 */
class DistributedLocker
{
    const DEFAULT_TIMEOUT_MINUTE = 1; //锁定过期时间，分钟
    const RETRY_TIMES = 100; //重试次数

    /**
     * lock
     * 获取锁
     * @author YQ
     * @param string $lockKey 锁的唯一标示
     * @param int $timeOutSec 锁超时时间
     * @access public
     * @return boolean
     */
    public static function lock($lockKey, $timeOutSec = self::DEFAULT_TIMEOUT_MINUTE)
    {
        if($timeOutSec <= 0){
            throw new Exception('Lock time value error');
        }

        $nowTry = 0;
        while($nowTry < self::RETRY_TIMES && !Cache::add($lockKey, 1, $timeOutSec)){
            usleep(200000);
            $nowTry++;
        }
        if($nowTry >= self::RETRY_TIMES){
            return false;
        }
        //非命令行自动释放
        if(PHP_SAPI != 'cli'){
            self::registerUnLock($lockKey);
        }
        return true;
    }

    /**
     * unLock
     * 释放锁
     * @author YQ
     * @param string $lockKey 锁的唯一标示
     * @access public
     * @return boolean
     */
    public static function unLock($lockKey)
    {
        $result = Cache::forget($lockKey);
        return $result ? true : false;
    }

    /**
     * registerUnLock
     * 增加自动释放锁机制
     * @author YQ
     * @param string $lockKey 锁的唯一标示
     * @access public
     * @return void
     */
    public static function registerUnLock($lockKey)
    {
        register_shutdown_function(function ($lockKey) {
            @\Cache::forget($lockKey);
        }, $lockKey);
    }
}
