<?php
/**
 * User: xiaoqiang
 * Mail：dr_cayman@163.com
 * blog: blog.ouoshop.com
 * Date: 2018/6/2
 * Time: 15:19
 */
namespace RoseKnife\Jianhang\hang\PayObj;
class RefundServlet{

    /**
     * @var string 交易码
     */
    public $TRANSCODE = "R3001";
    /*
     * @var 商户编号
     */
    public $CUST_NO;
    /*
     * @var 商户订单号
     */
    public $CUST_ORDER_NO;
    /*
     * @var 商户订单时间
     */
    public $CUST_ORDER_TIME;
    /*
     * @var 退款单号
     */
    public $REFUND_NO;
    /*
     * @var 订单金额
     */
    public $REFUND_AMOUNT;

    /**
     * @var 退款原因说明
     */
    public $REFUND_REASON;

    /**
     * @var 备用字段 1
     */
    public $ORDER_INFO1;

    /**
     * @var 备用字段 2
     */

    public $ORDER_INFO2;




}