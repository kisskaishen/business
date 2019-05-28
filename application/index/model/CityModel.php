<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/27
 * Time: 11:50
 */

namespace app\index\model;

use app\index\model\BaseModel;

class CityModel extends BaseModel
{
    protected $pk = 'id';
    protected $table = 'wap_cities';
}