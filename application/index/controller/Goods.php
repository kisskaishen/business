<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/21
 * Time: 16:28
 */

namespace app\index\controller;


use app\index\model\GoodsCommonModel;
use app\index\model\GoodsModel;
use app\index\model\SpecModel;
use app\index\model\SpecValueModel;
use think\App;
use think\Controller;
use think\Db;

class Goods extends Controller
{
    private $goods_common_model = [];
    private $spec_model = [];
    private $spec_value_model = [];
    private $goods_model = [];

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->goods_common_model = new GoodsCommonModel();
        $this->spec_model = new SpecModel();
        $this->spec_value_model = new SpecValueModel();
        $this->goods_model = new GoodsModel();
    }

    public function index()
    {
        echo '这里是商品';
    }

    public function get_list()
    {
        $data['goods_name'] = input('goods_name');
        $data['goods_status'] = input('goods_status');
        $where = [];
        $join = [];
        $field = '*';
        $order = 'goods_common_id desc';
        if (!empty($data['goods_name'])) {
            $where[] = ['goods_name', 'like', '%' . $data['goods_name'] . '%'];
        }
        if (!empty($data['goods_status'])) {
            $where[] = ['goods_status', 'like', '%' . $data['goods_status'] . '%'];
        }

        $res = $this->goods_common_model->getList($where, $join, $field, $order);
        return return_info(200, 'success', $res);
    }

    public function goods_detail()
    {
        $goods_common_id = input('goods_common_id');
        $where = [];
        $join = [];
        if (!empty($goods_common_id)) {
            $where['goods_common_id'] = $goods_common_id;
            $res = $this->goods_common_model->getInfo($where);
            $specArr = $this->goods_model->getList($where);
            foreach ($specArr as $k=>$v) {
                $v['goods_spec'] = json_decode($v['goods_spec']);
            }
            $res['goods_spec'] = $specArr;
            if (!$res) {
                return return_info();
            } else {
                return return_info(200, 'success', $res);
            }
        } else {
            return return_info();
        }
    }

    public function add_edit_goods()
    {
        $goods_common_id = input('goods_common_id');

        $data['goods_name'] = input('goods_name');
        $data['goods_detail'] = input('goods_detail');
        $data['goods_price'] = input('goods_price');
        $data['goods_img'] = input('goods_img');
        $data['detail_img'] = input('detail_img');
        $data['store_id'] = input('store_id');
        $data['classify_id'] = input('classify_id');
        $data['goods_status'] = input('goods_status');
        $data['remark'] = input('remark');

        $specData = json_decode(input('goods_spec'), true);

//        foreach ($data as $k => $v) {
//            if (empty($v)) {
//                return return_info(300, '请填写完整信息');
//            }
//        }
        if (!empty($goods_common_id)) {
            // 修改
            $res = $this->goods_common_model->updateDate($data);
            if (!$res) {
                return return_info(300, '编辑失败');
            }
            return return_info(200, 'success');

        } else {
            // 添加
            $res_goods_common_id = $this->goods_common_model->addGetId($data);

            if (!$res_goods_common_id) {
                return return_info(300, '添加失败');
            }

            foreach ($specData as $k=>$v) {
//                $specData[$k]['goods_spec'] = serialize($specData[$k]['goods_spec']);
                $specData[$k]['goods_name'] = input('goods_name');
                $specData[$k]['goods_img'] = '123123';
                $specData[$k]['goods_sales'] = '9';
                $specData[$k]['goods_common_id'] = $res_goods_common_id;
            }

            $goodsSpec = $this->goods_model->insertAllData($specData);
            if ($goodsSpec) {
                return return_info(200, 'success');
            } else {
                return return_info(300, '信息错误');
            }

        }
    }

    public function del_goods()
    {
        $where['id'] = input('goods_id');
        $res = $this->goods_model->deleteData($where);
        if ($res) {
            return return_info(200, '删除成功');
        } else {
            return return_info(300, '删除失败');
        }
    }


    // 添加规格名
    public function add_spec()
    {
        $data['spec_name'] = input('spec_name');
        $data['store_id'] = input('store_id');

        $where[] = ['spec_name', '=', $data['spec_name']];
        $where[] = ['store_id', '=', $data['store_id']];

        $spec = $this->spec_model->where($where)->find();
        if ($spec) {
            return return_info('300', '该规格名称已经添加');
        } else {
            $res = $this->spec_model->insertData($data);
            return return_info('200', 'success', $res);
        }
    }

    // 规格列表
    public function spec_list()
    {
        $data = [];
        if (!empty(input('store_id'))) {
            $data['store_id'] = input('store_id');
        }
        $res = $this->spec_model->getList($data);
        return return_info('200', 'success', $res);
    }

    // 添加规格值
    public function add_spec_value()
    {
        $data['spec_value_name'] = input('spec_value_name');
        $data['store_id'] = input('store_id');
        $data['spec_name'] = input('spec_name');
        $data['spec_id'] = input('spec_id');

        $spec_value = $this->spec_value_model->where($data)->find();
        if ($spec_value) {
            return return_info(300, '添加失败');
        } else {
            $res = $this->spec_value_model->insertData($data);
            return return_info(200, 'success');
        }
    }


}