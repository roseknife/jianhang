<?php
/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/6/2 0002
 * Time: 10:35
 */

namespace RoseKnife\Jianhang;

class Lite
{

    private $config;

    /**
     * Lite constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }


    /**
     * @param object $obj 订单对象
     * @param string $type 接口名称
     * @return array|mixed|string
     */
    public function exec($obj, $type)
    {
        $obj->CUST_NO = $this->config['cust_no'];
        $data = $this->changeData($obj);
        $result = $this->postData($this->config['url'] . $type, $data);
        $arr = $this->parse_url_param($result);


        if ($this->checksign($arr)) {
            return [
                'code' => 1,
                'data' => $arr
            ];
        } else {
            return ['code' => 0];
        }

    }


    private  function parse_url_param($str)
    {
        $data = array();
        $str=explode('?', $str);
        $parameter = explode('&', end($str));
        foreach ($parameter as $val) {
            $tmp = explode('=', $val);
            $data[$tmp[0]] = urldecode($tmp[1]);
        }
        return $data;
    }

    /**
     * @desc 检查签名
     * @param array $arr 数组数据
     * @return bool
     */
    public function checksign($arr)
    {
        if (!isset($arr['SIGN'])) {
            return false;
        }
        $sign = $arr['SIGN'];
        unset($arr['SIGN']);
        ksort($arr);
        $singStr = $this->ToUrlParams($arr);
        if ($this->verifySign($singStr, $this->config['publickey'], $sign)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $data 待签名的数据
     * @param string $privatekey 私钥字符串或私钥文件
     * @param string $signType 签名类型
     * @param bool $keyfromfile Key文件
     * @return string 签名字符串
     */
    public function alonersaSign($data,$privatekey,$signType = "RSA",$keyfromfile=false) {

        if(!$keyfromfile){
            $priKey=$privatekey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        }

        else{
            $priKey = file_get_contents($privatekey);
            $res = openssl_get_privatekey($priKey);
        }

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }

        if($keyfromfile){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }


    /**
     * @param $data
     * @param $publickey
     * @param $sign
     * @param string $signType
     * @return bool
     */
    function verifySign($data,$publickey, $sign,  $signType = 'RSA',$keyfromfile=false) {

        if(!$keyfromfile){

            $pubKey= $publickey;
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            //读取公钥文件
            $pubKey = file_get_contents($publickey);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }

        ($res) or die('公钥出错');

        //调用openssl内置方法验签，返回bool值

        if ("RSA2" == $signType) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }

        if($keyfromfile){
            openssl_free_key($res);
        }

        return $result;
    }

    /*
 * 拼接url 并对键值进行url转码
 */
    private function dataurlencode($data){

        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign"  && !is_array($v) ){
                $buff .= $k . "=" . urlencode($v) . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * @param $obj
     * @return string
     */
    public function changeData($obj)
    {
        $data = (array)$obj;
        ksort($data);
        $dataStr = $this->ToUrlParams($data);
        $signStr = '&SIGN=' . urlencode($this->alonersaSign($dataStr, $this->config['privatekey']));
        return $this->dataurlencode($data) . $signStr;
    }

    /*
     * 拼接字符串
     */
    private  function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign"  && !is_array($v) ){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * @param $url
     * @param $data
     * @param int $timeout
     * @return mixed
     */
    private function postData($url, $data, $timeout = 300)
    {
        $headers = array(
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded"
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER=>false

        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;


    }


}