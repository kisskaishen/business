<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/5/27
 * Time: 11:09
 */

namespace app\index\controller;


use app\index\model\AddressModel;
use app\index\model\AreaModel;
use app\index\model\CityModel;
use app\index\model\ProvinceModel;
use think\App;
use think\Controller;
use think\Db;

class Address extends Controller
{
    private $address_model = [];
    private $province_model = [];
    private $city_model = [];
    private $area_model = [];

    public function __construct(App $app = null)
    {
        $this->address_model = new AddressModel();
        $this->province_model = new ProvinceModel();
        $this->city_model = new CityModel();
        $this->area_model = new AreaModel();
        parent::__construct($app);
    }

    /**
     * @return mixed
     * 收货地址
     */
    public function address_list()
    {
        $user_id = input('user_id');
        $where = [];
        if (!empty($user_id)) {
            $where[] = ['user_id', '=', $user_id];
            $res = $this->address_model->getList($where);
            if ($res) {
                return return_info(200, 'success', $res);
            }
            return return_info();
        }
        return return_info();
    }

    /**
     * 添加/编辑收货地址
     */
    public function add_edit_address()
    {
        $address_id = input('address_id');//修改时候才有，主键
        $is_default = input('is_default');//是否默认，0：否，1：是

        $post_error = parameter_check(['user_id','province_id','province_name','city_id','city_name','area_id','area_name','detail','address','name','tel'],1);
        if($post_error['code'] == 300){
            return $post_error;
        }
        $data = $post_error['data'];
        $data['is_default'] = $is_default;
        if (!empty($address_id)) {
            $data['address_id'] = $address_id;
            $con[] = ['address_id','=',$address_id];
        }

        if($is_default == 1){//设置为默认值，其他的就要为0
            $con[] = ['is_default','=',1];
            $res = $this->address_model->save(['is_default'=>0],$con);//默认地址只能有一个
            if ($res) {
                return return_info(200,'success',$res);
            }
        }
        $res = $this->address_model->save($data);//save中含主键则是修改记录，不含则是添加记录
        if ($res) {
            return return_info(200,'success',$res);
        }
    }

    /**
     * 删除收货地址
     */
    public function del_address()
    {
        $user_id = input('user_id');
        $address_id = input('address_id');
        if (empty($user_id) || empty($address_id)) {
            return return_info();
        }
        $where[] = ['address_id', '=', $address_id];
        $res = $this->address_model->deleteData($where);
        if (res) {
            return return_info(200, 'success');
        }
        return return_info();
    }

    /**
     * 省市区
     */
    public function get_province()
    {
        $res = $this->province_model->getList();
        if ($res) {
            return return_info(200, 'success', $res);
        }
    }

    public function get_city()
    {
        $province_id = input('province_id');
        if (!empty($province_id)) {
            $where[] = ['province_id', '=', $province_id];

            $res = $this->city_model->getList($where);
            if ($res) {
                return return_info(200, 'success', $res);
            }
        }

    }

    public function get_area()
    {
        $city_id = input('city_id');
        if (!empty($city_id)) {
            $where[] = ['city_id', '=', $city_id];

            $res = $this->area_model->getList($where);
            if ($res) {
                return return_info(200, 'success', $res);
            }
        }
    }

}