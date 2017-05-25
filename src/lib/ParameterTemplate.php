<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170525 11:36
 * fileName :   ParameterTemplate.php
 */

namespace PHPLogicalDSL\lib;

/**
 * Class ParameterTemplate    规则引擎的参数模板,定义使用规则引擎时的输入和返回值
 *
 * @package PHPLogicalDSL\lib
 * @author  YangKe <yangke@xiaomi.com>
 */
abstract class ParameterTemplate
{
    /**
     * @var null|array 输入参数模板
     */
    public $input = null;
    /**
     * @var null|array 输入参数值域
     */
    public $inputRange = null;
    /**
     * @var null|array 返回值模板
     */
    public $output = null;

    /**
     * 检查参数是否被设置正确
     */
    public function check()
    {
        if (is_null($this->input) || is_null($this->inputRange) || is_null($this->output)) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41003], 41003);
        }
        if (!(is_array($this->input) && is_array($this->inputRange) && is_array($this->output))) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41003], 41003);
        }
    }
}