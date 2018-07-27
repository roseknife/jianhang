<?php
/**
 * Created by PhpStorm.
 * User: yinzhiqiang
 * Date: 2018/7/23
 * Time: 下午2:11
 */
namespace RoseKnife\Jianhang\PayObj;

class CheckServlet {

    /**
     * @var string 交易码
     */
    public $TRANSCODE = "Q2003";

    /**
     * @var 商户编号
     */
    public $CUST_NO;

    /**
     * @var 订单开始时间
     */
    public $BGN_ORDER_DATE;

    /**
     * @var 订单结束时间
     */
    public $END_ORDER_DATE;

    /**
     * @var 支付渠道 1：支付宝 2：微信 3:龙支付
     */
    public $PAY_PRODUCT;

    /**
     * @var 流水类型 1： 成功帐 2：平台多帐 3：渠道多帐
     */

    public $LIST_TYPE;

    /**
     * @var 页码  第一次输入1
     */

    public $PAGE;

    /**
     * @var 备用字段 1
     */

    public $ORDER_INFO1;

    /**
     * @var 备用字段 2
     *
     */

    public $ORDER_INFO2;

    /**
     * @var 签名模式
     */
    public $SIGN_TYPE = "RSA";



}
