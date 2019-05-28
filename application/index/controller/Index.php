<?php
namespace app\index\controller;

use think\Controller;
use think\Env;

class Index extends Controller
{
    public function index()
    {
        echo $this->fetch('index', [
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
//        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
//    public function path() {
//        echo Env::get('root_path');
//        echo Env::get('app_path');
//        echo Env::get('think_path');
//        echo Env::get('config_path');
//        echo Env::get('extend_path');
//    }
}
