<?php
/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/6/2 0002
 * Time: 11:07
 */
include_once "src/Lite.php";
include_once "src/PayObj/ClearQueryServlet.php";

$config = array(

);

$lite = new \RoseKnife\Jianhang\Lite($config);



$obj = new \RoseKnife\Jianhang\hang\PayObj\ClearQueryServlet();

$obj->ORDER_DATE = "201807021";
$obj->PAGE ="1";

$lite->ClearQueryServlet($obj);