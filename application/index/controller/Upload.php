<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/29
 * Time: 14:20
 */

namespace app\index\controller;


use think\Controller;

class Upload extends Controller
{

    public function upload()
    {
        $request = request();
        $file = $request->file('image');
        $type = input('type');

        if (empty($file) || empty($type)) {
            return return_info(300, '请求参数出错');
        }
        $date = date('Y') . '/' . date('m') . '/' . date('d');
        if (!is_dir(BASE_DATA_PATH)) {
            mkdir('BASE_DATA_PATH');
        }
        $info = $file->move(BASE_DATA_PATH);
        if ($info) {
            $con['image'] = $info->getFilename();
            $con['type'] = $type;
            $con['url'] = BASE_DATA_PATH . '/' . $date . '/' . $info->getFilename();
            return $con;
        }
    }
    public function uploadFile()
    {
        $request = request();
        $file = $request->file('file');
        var_dump($_FILE);
        if (empty($file)) {
            return return_info(300, '请求参数出错');
        }
        $date = date('Y') . '/' . date('m') . '/' . date('d');
        if (!is_dir(BASE_DATA_PATH)) {
            mkdir('BASE_DATA_PATH');
        }
        $info = $file->move(BASE_DATA_PATH);
        if ($info) {
            $con['image'] = $info->getFilename();
            $con['url'] = BASE_DATA_PATH . '/' . $date . '/' . $info->getFilename();
            return $con;
        }
    }
}