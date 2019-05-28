<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/27
 * Time: 11:49
 */

namespace app\index\model;

use app\index\model\BaseModel;

class ProvinceModel extends BaseModel
{
    protected $pk = 'id';
    protected $table = 'wap_provinces';
}