<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/12
 * Time: 17:13
 */

namespace app\index\controller;

use app\index\controller\User;

use think\Controller;

class Wxchat extends Controller
{
    private $appid = "wx4555b2d6a835b000";
    private $appsecret = "9d84f47b27062977e0adaf53eaae1804";

    //授权后重定向的回调链接地址， 请使用 urlEncode 对链接进行处理
    private $redirect_uri = 'http://www.fansmango.com/public/index.php/index/Wxchat/get_snsapi_userinfo';

    //用户同意授权，获取code
    public function get_snsapi_userinfo()
    {
        $loginMember = new User();
        var_dump(isset($_GET['code']));
        // 先判断是否有code
        if (!isset($_GET['code'])) {
            // 没有的话执行api获取code
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=" . urlencode($this->redirect_uri) . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header('Location:' . $url);
            exit;
        } else {
            $code = $_GET['code'];
            // 通过code换取网页授权access_token
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->appsecret&code=$code&grant_type=authorization_code";
            $output = get_request($url);
            $jsonInfo = json_decode($output,true);
            // 拉取用户信息(需scope为 snsapi_userinfo)
            $getInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=".$jsonInfo['access_token']."&openid=".$jsonInfo['openid']."&lang=zh_CN";
            // 获取微信的用户信息
            $info = file_get_contents($getInfoUrl);
            $memberArr = $loginMember->getMember($info);
            var_dump($memberArr);

        }
    }

}