<?php
/**
 * Created by PhpStorm.
 * User: yinzhiqiang
 * Date: 2018/7/26
 * Time: 下午3:45
 */
namespace RoseKnife\Jianhang\PayObj;

class ClearQueryServlet{
    /**
     * @var string 交易码
     */
    public $TRANSCODE = "S5003";

    /**
     * @var string 商户编号
     */
    public $CUST_NO;

    /**
     * @var string 订单支付日期
     */

    public $ORDER_DATE;

    /**
     * @var string 页码
     */

    public $PAGE;

    /**
     * @var string 备用字段 1
     */

    public $ORDER_INFO1;

    /**
     * @var string 备用字段 2
     */

    public $ORDER_INFO2;

    /**
     * @var string 备用字段 2
     */

    public $SIGN_TYPE = "RSA";
}