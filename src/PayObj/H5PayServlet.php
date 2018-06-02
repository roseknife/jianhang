<?php
/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/6/2 0002
 * Time: 10:41
 */
namespace RoseKnife\Jianhang\PayObj;

class H5PayServlet
{
    /**
     * @var string 交易码
     */
    public $TRANSCODE = "P1004";
    /**
     * @var 商户编号
     */
    public $CUST_NO;

    /**
     * @var 商户订单号
     */
    public $CUST_ORDER_NO;

    /**
     * @var 商户订单时间 如:20180313112247
     */
    public $CUST_ORDER_TIME;

    /**
     * @var 商品名称
     */
    public $COMMODITY_NAME;

    /**
     * @var 订单描述
     */
    public $ORDER_REMARK;

    /**
     * @var 订单金额 元
     */
    public $ORDER_AMOUNT;

    /**
     * @var 门店编号 商户自定义
     */
    public $STORE_ID;

    /**
     * @var 机具终端号
     */
    public $TERMINAL_ID;

    /**
     * @var 备用字段1
     */
    public $ORDER_INFO1;

    /**
     * @var 备用字段2
     */
    public $ORDER_INFO2;

    /**
     * @var 订单超时时间
     */
    public $ORDER_TIMEOUT;

    /**
     * @var 结果通知url
     */
    public $NOTIFY_URL;

    /**
     * @var 回调页面url
     */
    public $RETURN_URL;


}