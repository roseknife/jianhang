<?php
/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/6/2 0002
 * Time: 11:07
 */
include_once "src/Lite.php";
include_once "src/PayObj/H5PayServlet.php";

$config = array(
    "url" => "",
    "privatekey" => "",
    "publickey" => ""
);

$lite = new \RoseKnife\Jianhang\Lite($config);



$obj = new \RoseKnife\Jianhang\hang\PayObj\H5PayServlet();
$obj->CUST_NO = "3220066666";
$obj->COMMODITY_NAME = "letaob";
$obj->CUST_ORDER_NO = "222222112";
$obj->CUST_ORDER_TIME = "20180513112247";
$obj->NOTIFY_URL = "http://demo/demo.php";
$obj->RETURN_URL = "http://demo/demo.php";
$obj->ORDER_AMOUNT = "22.32";

$res = $lite->exec($obj, "h5PayServlet");
print_r($res);
