<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170525 11:16
 * fileName :   Simple1Params.php
 */
namespace PHPLogicalDSLTests\data;

use PHPLogicalDSL\lib\ParameterTemplate;


/**
 * Class Simple1Params
 *
 * @package PHPLogicalDSLTests\data
 * @author  YangKe <yangke@xiaomi.com>
 */
class Simple1Params extends ParameterTemplate
{
    /**
     * @var array
     */
    protected $input = [
        'order'   => [
            'order_id'      => null,
            'stock_channel' => null,
            'order_from'    => null,
            'order_type'    => null,
            'price'         => null,
            'mihome'        => null,
        ],
        'address' => [
            'province' => null,
            'city'     => null,
            'area'     => null,
        ],
    ];
    protected $inputRange = [
        'order'   => [
            'order_id'      => null,
            'stock_channel' => '("cn-order","cn-tmall","cn-mj","cn-taobao")', //a string set
            'order_from'    => '(1:6)', //1,2,3,4,5,6
            'price'         => '(0:+)', //from 0 to infinity
            'mihome'        => '(110,112,114,115,116)', //a number set
        ],
        'address' => [
            'province' => '(377,378,379,380,381,382,383,384,385,386,387,388,389,390,391,392,393,394,395,396)', //a number set
            'city'     => '(36:52,54:88)', //from 36 to 52 AND from 54 to 88
            'area'     => '(36:60,62,66:100)', //from 36 to 60 AND from 66 to 100
        ],
    ];
    protected $output = [
        'express' => null,
        'mihome'  => null,
    ];
}