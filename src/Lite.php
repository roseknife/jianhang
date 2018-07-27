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

    /** 对账单查询
     * @param object $obj 查询对象
     * @return  array|mixed|string
     */

    public function CheckServlet($obj){
        $obj->CUST_NO = $this->config['cust_no'];
        $data = $this->changeData($obj);
        $data =$this->xmlRevise($this->arrayToXml($this->parse_url_param_decode($data)));
        $result = $this->xmlToArray($this->postData($this->config['url'] . 'checkServlet', $data));

        if($result['RTN_CODE'] =="0000"){
            if(isset($result['QUERYORDER'])){
                foreach ($result['QUERYORDER'] as $k=>$v){
                    if(!$this->checksign($this->arrayRevise($v))){
                        return ['code' => 0];
                    }
                }
            }else{
                return [
                    'code' => 0
                ];
            }
            return [
                'code' => 1,
                'data' => $result['QUERYORDER']
            ];
        }else{
            return ['code' => 0];
        }
    }

    /** 清算查询
     * @param object $obj 查询对象
     * @return  array|mixed|string
     */
    public function ClearQueryServlet($obj){
        $obj->CUST_NO = $this->config['cust_no'];
        $data = $this->changeData($obj);
        $data =$this->xmlRevise($this->arrayToXml($this->parse_url_param_decode($data)));
        $result = $this->xmlToArray($this->postData($this->config['url'] . 'clearQueryServlet', $data));
        if($result['RTN_CODE'] =="0000"){
            if(isset($result['SHARING_RES'])){

                foreach ($result['SHARING_RES'] as $k=>$v){
                    if(!$this->checksign($this->arrayRevise($v))){
                        return ['code' => 0];
                    }
                }
                return [
                    'code' => 1,
                    'data' => $result['SHARING_RES']
                ];
            }else{
                return [
                    'code' => 0
                ];
            }
        }else{
            return ['code' => 0];
        }
    }


    public  function parse_url_param($str)
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

    public  function parse_url_param_decode($str)
    {
        $data = array();
        $str=explode('?', $str);
        $parameter = explode('&', end($str));
        foreach ($parameter as $val) {
            $tmp = explode('=', $val);
            $data[$tmp[0]] = $tmp[1];
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
    public function verifySign($data,$publickey, $sign,  $signType = 'RSA',$keyfromfile=false) {

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
    public function dataurlencode($data){

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
    public  function ToUrlParams($data)
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
    public function postData($url, $data, $timeout = 300)
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


    /**
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);//禁止引用外部xml实体
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values; //降维处理
    }

    /**
     * @param $xml
     * @return mixed
     */

    public function arrayToXml($arr)
    {
        $xml = "";
        if (!is_array($arr)) {
            return false;
        }
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $key = preg_replace('/\[\d*\]/', '', $key);
                $xml .= "<" . $key . ">" . $this->arrayToXml($value) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $value . "</" . $key . ">";
            }
        }
        return $xml;
    }


    /**
     * 对xml 修正
     * @param $xml
     * @return mixed
     */
    public function  xmlRevise($xml){
        return '<?xml version="1.0" encoding="UTF-8"?><DOCUMENT>'.$xml."</DOCUMENT>";
    }

    /**
     * 数组降维
     * @param array $arr
     * @return
     */
    public function arrayRevise($arr){

        foreach ($arr as $k=>&$v){
            if(is_array($v)){
                count($v)>1? $v=$v[0]:$v="";
            }
            $v = urldecode($v);
        }
        return $arr;
    }
}