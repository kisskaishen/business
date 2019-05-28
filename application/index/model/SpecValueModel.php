<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/22
 * Time: 16:11
 */

namespace app\index\model;

use app\index\model\BaseModel;

class SpecValueModel extends BaseModel
{
    protected $pk = 'spec_value_id';
    protected $table = 'wap_spec_value';
}