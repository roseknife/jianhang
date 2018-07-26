<?php
/**
 * User: xiaoqiang
 * Mail：dr_cayman@163.com
 * blog: blog.ouoshop.com
 * Date: 2018/6/2
 * Time: 16:44
 */

namespace RoseKnife\Jianhang\PayObj;


class OrderCloseServlet extends PayObjBase
{

    public $CUST_NO;

    /**
     * @var string 交易码
     */
    public $TRANSCODE = "C4001";


    /**
     * @var 商户订单号
     */
    public $CUST_ORDER_NO;

    /**
     * @var 商户订单时间
     */
    public $CUST_ORDER_TIME;

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