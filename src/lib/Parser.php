<?php
namespace PHPLogicalDSL\lib;

/**
 * Class Parser    解析DSL语句
 *
 * @package PHPLogicalDSL\lib
 * @author  YangKe <yangke@xiaomi.com>
 */
class Parser extends SingletonInstance
{
    /**
     * 解析传入的脚本为逻辑树
     *
     * @param $script
     * @return array
     */
    public function parse($script)
    {
        return [];
    }
}