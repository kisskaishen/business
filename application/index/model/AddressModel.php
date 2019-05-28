<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/27
 * Time: 11:06
 */

namespace app\index\model;

use app\index\model\BaseModel;

class AddressModel extends BaseModel
{
    protected $pk = 'address_id';
    protected $table = 'wap_address';
}