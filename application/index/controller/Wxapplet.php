<?php
/**
 * Created by PhpStorm.
 * User: qwk
 * Date: 2019/4/13
 * Time: 02:27
 */

namespace app\index\controller;

use app\index\controller\User;

include_once "ErrorCode.php";

use think\Controller;

class Wxapplet extends Controller
{
    private $appid = 'wx975e6a0441719c00';
    private $secret = '08439cc50db83b1a47fcd22a54ec53ba';
    private $sessionKey = '';

    public function get_applet_member()
    {
        $loginMember = new User();

        $code = input('code');
        $encryptedData = input('encryptedData');
        $iv = input('iv');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$this->appid&secret=$this->secret&js_code=$code&grant_type=authorization_code";
        $c_url = get_request($url);
        $infoArr = json_decode($c_url, true);
        if (array_key_exists('openid', $infoArr)) {
            $this->sessionKey = $infoArr['session_key'];
        } else {
            return return_info();
        }
        $errCode = $this->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {    //成功解密所有信息返回
            $data = json_decode($data, true);
            if (array_key_exists('unionid',$data)) {
                $json_info['unionid'] = $data['unionId'];
            }
            $json_info['openid'] = $data['openId'];
            $json_info['nickname'] = $data['nickName'];
            $json_info['sex'] = $data['gender'];
            $json_info['headimgurl'] = $data['avatarUrl'];
            //下面三个没用到，但还是传一下好了
            $json_info['city'] = $data['city'];
            $json_info['province'] = $data['province'];
            $json_info['country'] = $data['country'];
            //保存json后的数据
            $memberArr = $loginMember->getMember(json_encode($json_info));
            return $memberArr;
        } else {
            return return_info('300', $errCode);
        }
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData( $encryptedData, $iv, &$data )
    {
        if (strlen($this->sessionKey) != 24) {
            return ErrorCode::$IllegalAesKey;
        }
        $aesKey=base64_decode($this->sessionKey);
        if (strlen($iv) != 24) {
            return ErrorCode::$IllegalIv;
        }
        $aesIV=base64_decode($iv);
        $aesCipher=base64_decode($encryptedData);
        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return ErrorCode::$IllegalBuffer;
        }
        if( $dataObj->watermark->appid != $this->appid )
        {
            return ErrorCode::$IllegalBuffer;
        }
        $data = $result;
        return ErrorCode::$OK;

    }
}