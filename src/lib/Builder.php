<?php
namespace PHPLogicalDSL\src\lib;

/**
 * Class Builder 构造DSL语句
 *
 * @package PHPLogicalDSL\src\lib
 * @author  YangKe <yangke@xiaomi.com>
 */
class Builder extends SingletonInstance
{
    public $when = [];
    public $then = [];

    public function when()
    {

        return self::$instance[get_class()];
    }
}