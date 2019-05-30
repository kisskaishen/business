<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/29
 * Time: 18:14
 */

namespace app\index\model;
use app\index\model\BaseModel;

class PayModel extends BaseModel
{
    protected $pk = 'pay_id';
    protected $table = 'wap_pay';
}