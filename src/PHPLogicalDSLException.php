<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 15:08
 * fileName :   PHPLogicalDSLException.php
 */

namespace PHPLogicalDSL;
/**
 * Class PHPLogicalDSLException    DSL异常
 *
 * @package PHPLogicalDSL
 * @author  YangKe <yangke@xiaomi.com>
 */
class PHPLogicalDSLException extends \Exception
{

    public function __construct($message, $code, Exception $previous)
    {
        parent::__construct($message, $code, $previous);
    }
}