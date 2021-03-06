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
use app\index\model\OrderGoodsModel;
use app\index\model\OrderModel;
use app\index\model\PayModel;
use app\index\model\UserModel;
use think\Db;

class OrderLogic
{
    private $user_id = 0;
    private $goods_id = [];       // 商品id列表
    private $address = '';       // 收货地址
    private $pay_sn = '';          // 支付单号
    private $order_id = '';         // 订单id
    private $order_sn = '';         // 订单号

    private $order_operator = [];       // 生成订单号、支付单号信息
    private $order_model = [];          // 订单表

    private $user_model = [];        // 用户信息
    private $address_model = [];        // 收货地址
    private $goods_model = [];        // 商品信息
    private $pay_model = [];        // 支付信息
    private $order_goods_model = [];        // 订单商品信息


    public function __construct()
    {
        $this->order_operator = new OrderOperator();
        $this->order_model = new OrderModel();
        $this->address_model = new AddressModel();
        $this->user_model = new UserModel();
        $this->goods_model = new GoodsModel();
        $this->pay_model = new PayModel();
        $this->order_goods_model = new OrderGoodsModel();
    }

    /**
     * 检查登陆状态
     */
    public function check_login($user_id)
    {
        if (empty($user_id)) {
            throw new \Exception("请选登陆");
        }
        $where[] = ['user_id', '=', $user_id];
        $res = $this->user_model->where($where)->find();      // 无结果

        if (!$res) {
            throw new \Exception('用户不存在');
        }
        $this->user_id = $user_id;
        return true;
    }

    /**
     * 检查商品库存等
     */
    public function check_goods($goods_list)
    {
        $goods_arr_id = [];
        $goods_arr_number = [];

        foreach ($goods_list as $k => $v) {
            array_push($goods_arr_id,explode('|',$v)[0]);
            array_push($goods_arr_number,explode('|',$v)[1]);
        }
        $goods_arr_id_str = implode(',',$goods_arr_id);
        $this->goods_id = $goods_arr_id_str;
        $where[] = ['goods_id','in',$goods_arr_id_str];
        $get_goods_list = $this->goods_model->getList($where);
        foreach ($get_goods_list as $k=>$v) {
            foreach ($goods_arr_number as $kk=>$vv) {
                if ($k==$kk) {
                    if ($v['goods_stock']<$vv) {
                        throw new \Exception('商品库存不足');
                    }
                }
            }
        }

    }


    /**
     * 检查收货地址等
     */
    public function check_address($address_id)
    {
        if (empty($address_id)) {
            throw new \Exception('请先选择收货地址');
        }
        $where[] = ['address_id', '=', $address_id];
        $res = $this->address_model->where($where)->find();
        if (!$res) {
            throw new \Exception('收货地址不存在');
        }
        return true;

    }


    /**
     * 下单
     */
    public function create_order($pay_money)
    {
        // 生成支付单号
        $pay_sn = $this->order_operator->makePaySn($this->user_id);
        $this->pay_sn = $pay_sn;

        $order_pay = [];
        $order_pay['pay_sn'] = $pay_sn;
        $order_pay['pay_money'] = $pay_money;
        $order_pay['user_id'] = $this->user_id;

        $pay_id = $this->pay_model->insertData($order_pay);         // 数据保存到pay库里面

        // 生成订单号
        $order_sn = $this->order_operator->makeOrderSn($pay_id);
        $order_info = [];
        $order_info['order_sn'] = $order_sn;
        $order_info['user_id'] = $this->user_id;
        $order_info['pay_sn'] = $pay_sn;
        $order_info['order_money'] = $pay_money;

        $order_res = $this->order_model->insertGetId($order_info);         // 数据保存到order库里面


        // 保存商品
        $where[] = ['goods_id', 'in', $this->goods_id];
        $goods_res = $this->goods_model->where($where)->field('goods_sales,goods_detail,goods_stock', true)->select()->toArray();
        foreach ($goods_res as $k=>$v) {
            $goods_res[$k]['order_id'] = $order_res;
        }
        $order_goods_res = $this->order_goods_model->insertAllData($goods_res);      // 数据保存到order_goods库里面
        if (!$order_goods_res) {
            return false;
        }

        if ($order_res) {
            return $order_res;
        }
    }

}