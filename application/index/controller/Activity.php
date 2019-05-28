<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/10
 * Time: 17:04
 */

namespace app\index\controller;


use app\index\model\activityModel;
use think\App;
use think\Controller;
use think\Db;

class Activity extends Controller
{
    private $activity_model = [];

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->activity_model = new activityModel();
    }

    // 活动列表
    public function index()
    {
        $where = [];
        $join = [];
        $field = '*';
        $order = 'a.id desc';
        $res = $this->activity_model->getList($where, $join, $field, $order);
        foreach($res as $k=>$v){
            $res[$k]['classify_name_arr'] =explode(',',$v['classify_name']);
        }
        return return_info(200, 'success', $res);
    }

    // 添加活动
    public function addActivity()
    {
        $data['name'] = input('post.name');
        $data['list_img'] = input('post.listImg');
        $data['detail_img'] = input('post.detailImg');
        $data['detail_doc'] = input('post.detailDoc');
        $data['classify_id'] = input('post.classifyId');
        $data['classify_name'] = input('post.classifyName');
        $resId = $this->activity_model->addGetId($data);
        $res['id'] = $resId;
        return return_info(200, '添加成功', $res);

    }

    // 编辑活动
    public function editActivity()
    {
        $id = input('post.id');
        $where[] = [];
        if (!empty($id)) {
            $data['name'] = input('post.name');
            $data['list_img'] = input('post.listImg');
            $data['detail_img'] = input('post.detailImg');
            $data['classify_id'] = input('post.classifyId');
            $data['classify_name'] = input('post.classifyName');
            $res = $this->activity_model->where('id',$id)->updateDate($data);
            return return_info(200, '修改成功');
        } else {
            return return_info(300, '信息错误');

        }
    }

    // 删除活动
    public function deleteActivity()
    {
        $id = input('post.id');
        $where['id'] = $id;
        if (!empty($id)) {
            $res = $this->activity_model->deleteData($where);
            return return_info(200, '删除成功');
        }
    }

    // 活动点击数+1
    public function incClickNum() {
        $id = input('post.id');
        if (!empty(input('post.id'))) {
            $res = $this->activity_model->where('id',$id)->setInc('clickNum');
            return return_info(200,'success',$res);
        }
    }

}