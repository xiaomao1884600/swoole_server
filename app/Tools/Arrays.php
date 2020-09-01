<?php
namespace App\Tools;

use Illuminate\Support\Arr;

class Arrays extends Arr{
	
	/**
	 * 检测 非空数组
	 * @param unknown $arr
	 * @param unknown $key
	 * @author agx
	 */
	public static function isNotEmptyArray($arr,$key){
		return isset($arr[$key]) && is_array($arr[$key]) && $arr[$key] ? true : false;		
	}
	
	/**
	 * 过滤数组列
	 * @param unknown $array
	 * @param unknown $columns
	 * @param array $result
	 * @author agx
	 */
	public static function selectColumns($array,$columns,$result = []){
		foreach($array as $key => $value){
			if(is_array($value)){
				$result[$key] = self::SelectColumns($value, $columns);
			}else{
				if(in_array($key , $columns)){
					$result[$key] = $value;
				}
			}
		}
		return $result;
	}
	
	/**
	 * 数组参数替换
	 * @param unknown $records
	 * @param unknown $params
	 * @return unknown
	 * @author agx
	 */
	public static function replaceParams(&$records , $params){
		foreach($records as $key => &$value){
			if(is_array($value)){
				self::replaceParams($value, $params);
			}else{
				if(isset($params[$key]) && isset($params[$key][$value])){
					$records[$key] = $params[$key][$value];
				}
			}
		}
	}
	
	public static function dicToArray($dic){
		
		if(!is_array($dic))	return [];
		$arr = [];
		foreach($dic as $k => $v){
			$arr[] = [
					'id'	=>	$k,
					'value'	=>	$v
			];
		}
		return $arr;
	}
}