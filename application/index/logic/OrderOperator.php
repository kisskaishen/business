<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/24
 * Time: 16:57
 */

namespace app\index\logic;


class OrderOperator
{
    /**
     * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
     * 长度 =2位 + 10位 + 3位 + 3位  = 18位
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @return string
     */
    public function makePaySn($member_id)
    {
        return mt_rand(10, 99)
            . sprintf('%010d', time() - 946656000)
            . sprintf('%03d', (float)microtime() * 1000)
            . sprintf('%03d', (int)$member_id % 1000);
    }

    /**
     * 订单编号生成规则，n(n>=1)个订单表对应一个支付表，
     * 生成订单编号(年月取4位 + $pay_id取8位 + 第N个子订单取2位+日分取4位)
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @param $pay_id 支付表自增ID
     * @return string
     */
    public function makeOrderSn($pay_id)
    {
        //记录生成子订单的个数，如果生成多个子订单，该值会累加
        static $num;
        if (empty($num)) {
            $num = 1;
        } else {
            $num++;
        }
        return date('Ym') . sprintf('%08d', $pay_id) . sprintf('%02d', $num) . date('di');
    }

}