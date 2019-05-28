<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/29
 * Time: 10:53
 */

namespace app\index\controller;


use app\index\model\ClassifyModel;
use think\App;
use think\Controller;
use think\Db;

class Classify extends Controller
{
    private $classify_model = [];
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->classify_model = new ClassifyModel();
    }

    public function index() {
        $where = [];
        $name = input('name');
        $join = [];
        $field = '*';
        $order = 'clickNum desc,id asc';
        if (!empty($name)) {
            $where[] = ['name','like','%'.$name.'%'];
        }
        $res = $this->classify_model->getList($where,$join,$field,$order);
        return return_info(200,'success',$res);
    }

    public function addClassify() {
        $data['name'] = input('post.name');
        $data['color'] = input('post.color');
        $res = $this->classify_model->addGetId($data);
        return return_info(200,'success',$res);
    }

    public function editClassify() {
        $data['id'] = input('post.id');
        $data['name'] = input('post.name');
        $data['clickNum'] = input('post.clickNum');
        $data['color'] = input('post.color');
        if (!empty(input('post.id'))) {
            $res = $this->classify_model->updateDate($data);
            return return_info(200,'修改成功',$res);
        } else {
            return return_info(300,'信息错误');
        }
    }

    public function delClassify() {
        $data['id'] = input('post.id');
        $res = $this->classify_model->deleteData($data);
        return return_info(200,'删除成功',$res);
    }

    public function delAllClassify() {
        $idArr = input('post.idArr');
        $where[] = ['id','in',$idArr];
        $res = $this->classify_model->deleteAllData($where);
        return return_info(200,'删除成功',$res);
    }

    public function incClickNum() {
        $id = input('post.id');
        if (!empty(input('post.id'))) {
            $res = $this->classify_model->where('id',$id)->setInc('clickNum');
            return return_info(200,'success',$res);
        }
    }

}