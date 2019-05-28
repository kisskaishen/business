<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/22
 * Time: 16:09
 */

namespace app\index\model;

use app\index\model\BaseModel;

class SpecModel extends BaseModel
{
    protected $pk = 'spec_id';
    protected $table = 'wap_spec';
}