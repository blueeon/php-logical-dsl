<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170525 12:31
 * fileName :   RuleFunction.php
 */

namespace PHPLogicalDSL\src\lib;

/**
 * Class RuleFunction    规则内嵌方法模板
 *
 * @package PHPLogicalDSL\src\lib
 * @author  YangKe <yangke@xiaomi.com>
 */
abstract class RuleFunction extends SingletonInstance
{
    /**
     * 入口方法
     *
     * @param $params
     * @return mixed
     */
    abstract public function run($params);
}