<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// 输出json
function return_info($code = '300', $message = '信息错误', $data = null)
{
    $arr['code'] = $code;
    $arr['message'] = $message;
    if ($data !== null) {
        $arr['data'] = $data;
    }
    return $arr;
}

/**
 * 参数检查 用于新增数据
 * @param $arr  需要接收的字段的数组集合
 * @param $type 0：字段是否存在；1：需要判断是否为空
 * @return mixed
 */
function parameter_check($arr, $type = 0){
    $arr = array_flip($arr);    //键值反转
    $arr_data = array_intersect_key($_POST, $arr);  //获取数组中所需元素组成新的数组，用来安全接受数据
    if($type == 1){ //去除空值，用于判断数据是否为空
        $arr_data = array_filter($arr_data);    //去除false，null，''，0
    }
    //array_diff_key() 返回一个数组，该数组包括了所有出现在 array1 中但是未出现在任何其它参数数组中的键名的值。
    $arr_data_check = array_diff_key($arr, $arr_data);   //数组比较返回差值
    //检查返回所缺参数
    if(count($arr_data_check) > 0){
        $error_message = implode(',',array_keys($arr_data_check));
        return return_info(300, $error_message.'参数异常,请检查表单');
    }
    return return_info(200, '验证通过', $arr_data);
}



/**
 * 构造http请求  目前只支持get,post
 * @param $url
 * @param array $data  post数据
 * @return mixed
 */
function get_request($url,$data=[]) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_PROXY, "127.0.0.1"); //代理服务器地址
//        curl_setopt($curl, CURLOPT_PROXYPORT, 8888); //代理服务器端口
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}