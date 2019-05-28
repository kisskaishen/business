<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/24
 * Time: 16:59
 */

namespace app\index\logic;

use app\index\logic\OrderOperator;
use app\index\model\AddressModel;
use app\index\model\GoodsModel;
use app\index\model\OrderModel;
use app\index\model\UserModel;

class OrderLogic
{
    private $user_id = 0;
    private $goods_stock = 0;       // 商品库存
    private $address = '';       // 收货地址
    private $pay_sn = '';          // 支付单号
    private $order_id = '';         // 订单id
    private $order_sn = '';         // 订单号

    private $order_operator = [];       // 生成订单号、支付单号信息
    private $order_model = [];          // 订单表

    private $user_model = [];        // 用户信息
    private $address_model = [];        // 收货地址
    private $goods_model = [];        // 商品信息


    public function __construct()
    {
        $this->order_operator = new OrderOperator();
        $this->order_model = new OrderModel();
        $this->address_model = new AddressModel();
        $this->user_model = new UserModel();
        $this->goods_model = new GoodsModel();
    }

    /**
     * 检查登陆状态
     */
    public function check_login()
    {
        $user_id = input('user_id');
        if (empty($user_id)) {
            return return_info(300, '请选登陆');
        }
        $res = $this->user_model->where(['user_id', '=', $user_id])->find();
        if ($res) {
            return true;
        }
    }

    /**
     * 检查商品库存等
     */
    public function check_goods()
    {
        $goods_id = input('goods_id');
        $goods_number = input('goods_numer');

        $is_exist = $this->goods_model->where('goods_id',$goods_id)->find();

        if (!$is_exist) {
            return return_info('300','商品不存在');
        }

    }

    /**
     * 检查收货地址等
     */
    public function check_address()
    {
        $address_id = input('address_id');
        if (empty($address_id)) {
            return return_info(300, '请先选择收货地址');
        }
        $res = $this->address_model->where(['address_id', '=', $address_id])->find();
        if ($res) {
            return true;
        }
    }


    /**
     * 下单
     */
    public function create_order()
    {
        // 生成支付单号
        $pay_sn = $this->order_operator->makePaySn($this->user_id);
        $this->pay_sn = $pay_sn;

        $order_pay = [];
        $order_pay['pay_sn'] = $pay_sn;
        $order_pay['user_id'] = $this->user_id;

        // 生成订单号
        $order_sn = $this->order_operator->makeOrderSn();
        $order_info = [];
        $order_info['order_sn'] = $order_sn;
        $order_info['user_id'] = $this->user_id;

        $res = $this->order_model->insertData($order_info);

        if ($res) {
            return return_info(200, '下单成功～', $res);
        }
    }

}