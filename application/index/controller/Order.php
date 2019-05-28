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

class Order extends Controller
{
    private $order_model = [];
    public function __construct(App $app = null)
    {
        $this->order_model = new OrderModel();

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
}