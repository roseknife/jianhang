<?php
/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/6/2 0002
 * Time: 11:07
 */
include_once "src/Lite.php";
include_once "src/PayObj/ClearNotifyServlet.php";

$config = array(
    'cust_no'=>'3220066666',
    'url'=>'http://test.jianhang.szwyll.com/payment/',
    'privatekey'=>'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIuogATOV2YDPNfY7QMM2nz7/mqSs/iejf1eNfT0F6IQT6TbC9hLxzTkIWGbv7AF+QH2aXNjAAZnfvuk1FSu6kXfJfXVnz4xKeUZ/UdnQBJU3JXI9nLQakvDyVmkMZ6KgjSszjOQo0WdDVBYb11AW1BrcCS6g8fcIB7SiGZs61t1AgMBAAECgYAY0/zmXdSV0y+G5A7Gwws4uwfw5GHQaket6ojHInntGt893J9PdbFVitUQaEL8xSMOoUL/+3KusMmXbSc+YtZNDhCHuJVXqMvevyybdm4UXZ+9FCIFSBJkXUdDTRkvogSRt+Os4NcP7txvqYOYIaWvotim7KO7GzFBiqUd9+qkAQJBAPaVwMVH5rvv4j72XDlDuG3t3nqIqAQYWqkYa+P9ca6yRdQ+ZKi8886Q1YSmeFLJWeoLaVXccnPnlR8JS4PPPwECQQCQ/ZclXgmx1jNkPJVJ8dS2TiunIQ46scaSsL191ilRDWU+s9628lJKqnp6EOItGKWzjNCL0OOR6puWvf+ew5B1AkBVOpWSmrOJqfRNRuHBeUK53EnVmH5aTACqCaLg/qzYcQ3pulcYa5bpgu4KF2/nTWkimCckYjm9DgJg49mSCYQBAkAmcsj5BMXnXdkE9LfWAwYYTgRvbmmakPgKgEnPq9ILB1VY2lPuCbZezHRsGDwSH14ZjfbjjMI8OI2H3NyDWnLdAkEAnYXRk/Wu6pxeugyGvJoOq6f1cBnNzN4Rgzk5R5kqGICrGfRLu3s3A7NvVixEWvw8TD7JaPgrdJDG4KoT3M+ucw==',
    'publickey'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDaLMeKQ0ThUbDnoTspWk5XUjn4M6h6uFBoI8S0WKD0Lyb15vVI1KAcF2NeiV/SReAgpYdY4FfZ7c+ZJq9FntEK0J/T7OOwxGRQPYZzqxxl1rdjUfDfEJ6OQZzAL4Xrtr4ONAqFgcCBPfWQdA/cR5v3y9grW/mlh1T4dtsY9qLSiwIDAQAB'
);

$lite = new \RoseKnife\Jianhang\Lite($config);



$obj = new \RoseKnife\Jianhang\PayObj\RefundQueryServlet();

$obj->ORDER_DATE='20180721';
$obj->GET_URL='http://test.api.sz-trip.com?s=App.Notify.JhClearingNotify';
var_dump($lite->exec($obj,'clearNotifyServlet'));


