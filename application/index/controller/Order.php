<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/24
 * Time: 14:25
 */

namespace app\index\controller;


use app\index\model\OrderModel;
use think\App;
use think\Controller;
use app\index\logic\OrderLogic;

class Order extends Controller
{
    private $order_model = [];
    private $order_login = [];
    public function __construct(App $app = null)
    {
        $this->order_model = new OrderModel();
        $this->order_login = new OrderLogic();
        parent::__construct($app);
    }

    // 订单列表
    public function order_list() {
        $where = [];
        $res = $this->order_model->getList($where);
        if (!$res) {
            return return_info();
        }
        return return_info(200,'success',$res);
    }
    // 订单详情
    public function order_detail() {
        $order_id = input('post.order_id');
        $where = [];
        if (empty($order_id)) {
            return return_info();
        }
        $where[] = ['order_id','=',$order_id];
        $res = $this->order_model->getInfo($where);
        if (!$res) {
            return return_info();
        }
        return return_info(200,'success',$res);
    }

    /**
     * 下单
     */
    public function set_order() {
        $goods_id=input('goods_id');
        $res = $this->order_login->check_goods();
        var_dump($res);
    }
}