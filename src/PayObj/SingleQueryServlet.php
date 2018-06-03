<?php
/**
 * User: xiaoqiang
 * Mail：dr_cayman@163.com
 * blog: blog.ouoshop.com
 * Date: 2018/6/2
 * Time: 15:14
 */

namespace RoseKnife\Jianhang\PayObj;

class  SingleQueryServlet extends PayObjBase
{
    /**
     * @var string 交易码
     */
    public $TRANSCODE = "Q2001";


    /*
     * @var 商户订单号
     */
    public $CUST_ORDER_NO;

    /*
     * @var 商户订单时间
     */
    public $CUST_ORDER_TIME;

    /*
     * @var 备用字段1
     */
    public $ORDER_INFO1;

    /*
     * @var 备用字段2
     */
    public $ORDER_INFO2;

    /**
     * @var 签名模式
     */
    public $SIGN_TYPE = "RSA";


}