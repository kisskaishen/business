<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/29
 * Time: 18:47
 */

namespace app\index\model;

use app\index\model\BaseModel;

class OrderGoodsModel extends BaseModel
{
    protected $pk = 'order_goods_id';
    protected $table = 'wap_order_goods';
}