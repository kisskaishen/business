<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/22
 * Time: 16:14
 */

namespace app\index\model;

use app\index\model\BaseModel;

class GoodsModel extends BaseModel
{
    protected $pk = 'goods_id';
    protected $table = 'wap_goods';
}