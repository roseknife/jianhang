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
        $data = $this->changeData($obj);
        $result = $this->postData($this->config['url'] . $type, $data);
        parse_str($result, $arr);

        if ($this->checksign($arr)) {
            return [
                'code' => 0,
                'data' => $arr
            ];
        } else {
            return ['code' => 0];
        }

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
        $singStr = http_build_query($arr);
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
    private function alonersaSign($data, $privatekey, $signType = "RSA")
    {
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($privatekey, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
        if ($signType == "RSA2") {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        return base64_encode(openssl_sign($data, $sign, $res));
    }


    /**
     * @param $data
     * @param $publickey
     * @param $sign
     * @param string $signType
     * @return bool
     */
    private function verifySign($data, $publickey, $sign, $signType = 'RSA')
    {
        $res = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($publickey, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        if ($signType == "RSA2") {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        return $result;
    }

    /**
     * @param $obj
     * @return string
     */
    public function changeData($obj)
    {
        $data = (array)$obj;
        ksort($data);
        $dataStr = http_build_query($data);
        $signStr = '&SIGN=' . urlencode($this->alonersaSign($dataStr, $this->config['privatekey']));
        foreach ($data as $k => &$v) {
            $v = urlencode($v);
        }
        return http_build_query($data) . $signStr;
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
            "Content-type: application/x-www-form-urlencoded;charset='utf-8'",
            "Accept: */*",
            "Cache-Control: no-cache",
            "Pragma: no-cache"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }


}