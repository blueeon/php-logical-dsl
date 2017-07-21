<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170718 17:53
 * fileName :   CommonFunction.php
 */

namespace PHPLogicalDSLTests\data;

class CommonFunction
{
    /**
     * 返回旧仓库
     *
     * @static
     * @param $homeId
     * @return mixed
     */
    public static function getMihome($homeId)
    {
        return [
            100 => '北京仓（旧）',
            112 => '北京仓',
        ][$homeId];
    }
}