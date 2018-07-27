<?php
/**
 * Created by PhpStorm.
 * User: yinzhiqiang
 * Date: 2018/7/24
 * Time: 上午9:06
 */
namespace RoseKnife\Jianhang\PayObj;

class ClearNotifyServlet{

    /**
     * @var string 交易码
     */
    public $TRANSCODE = "S5001";
    /**
     * @var string 商户编号
     */
    public $CUST_NO;

    /**
     * @var string 订单支付日期
     */

    public $ORDER_DATE;

    /**
     * @var 请求地址
     */

    public $GET_URL;

    /**
     * @var 备用字段 1
     */
    public $ORDER_INFO1;

    /**
     * @var 备用字段 2
     */
    public $ORDER_INFO2;

    /**
     * @var 签名模式
     */
    public $SIGN_TYPE = "RSA";


}