<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/11
 * Time: 17:00
 */

namespace app\index\model;

use app\index\model\BaseModel;

class UserModel extends BaseModel
{
    protected $pk = 'id';
    protected $table = 'wap_user';
}