<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/24
 * Time: 14:25
 */

namespace app\index\model;

use app\index\model\BaseModel;

class OrderModel extends BaseModel
{
    protected $pk = 'order_id';
    protected $table = 'wap_order';
}