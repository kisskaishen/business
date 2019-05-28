<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/11
 * Time: 17:00
 */

namespace app\index\controller;


use app\index\model\UserModel;
use think\App;
use think\Controller;

class User extends Controller
{
    private $user_model= [];

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->user_model = new UserModel();
    }

    // 获取会员列表
    public function getMemberList() {
        $id = input('id');
        $where = [];
        if (!empty($id)) {
            $where['id'] = $id;
        }
        $join = [];
        $field = '*';
        $order = 'a.id desc';
        $res = $this->user_model->getList($where,$join,$field,$order);
        return return_info(200,'获取成功',$res);
    }


    // 判断会员信息
    public function getMember($json_info=''){
        if (empty($json_info)) {
            return return_info();
        }
        $info = json_decode($json_info,true);
        $openid = $info['openid'];
        $sel = $this->user_model->where('openid',$openid)->find();
        if (!$sel) {
            $res = $this->wx_reg($info);
            return return_info('200', '注册成功',$res);

        } else {
            return return_info(300,'会员已注册');
        }
    }

    // 微信注册
    public function wx_reg($info) {
        $con['name'] = 'wx_'.time();
        $con['nickname'] = $info['nickname'];
        $con['picture'] = $info['headimgurl'];
        $con['openid'] = $info['openid'];
        $memberId = $this->user_model->addGetId($con);
        $con['id'] = $memberId;

        return $con;
    }

    // 绑定手机号
    public function bindingTel(){
        $id = input('id');
        $data['tel'] = input('tel');
        $res = $this->user_model->where('id',$id)->update($data);
        var_dump($res);
    }

}