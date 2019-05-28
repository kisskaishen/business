<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/22
 * Time: 16:14
 */

namespace app\index\model;

use app\index\model\BaseModel;

class GoodsSpecModel extends BaseModel
{
    protected $pk = 'goods_spec_id';
    protected $table = 'wap_goods_spec';
}