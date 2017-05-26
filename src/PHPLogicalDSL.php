<?php

/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 11:55
 * fileName :   PHPLogicalDSL.php
 */
namespace PHPLogicalDSL;

use phpDocumentor\Reflection\Types\Object_;
use PHPLogicalDSL\lib\Builder;
use PHPLogicalDSL\lib\ParameterTemplate;
use PHPLogicalDSL\lib\Parser;


/**
 * Class PHPLogicalDSL
 *
 * @package PHPLogicalDSL
 * @author  YangKe <yangke@xiaomi.com>
 */
class PHPLogicalDSL
{
    /**
     * @var null 规则正文
     */
    private $rule = null;

    /**
     * @var null 解析过的脚本
     */
    private $ruleParsed = null;

    /**
     * @var null 请求参数定义
     */
    private $input = null;
    /**
     * @var null 请求参数的值域
     */
    private $inputRange = null;

    /**
     * @var null 返回结果定义
     */
    private $output = null;

    /**
     * 加载一个规则,并且完成语法检查,解析
     *
     * @param string $script DSL script
     * @param object $params params object extends from ParameterTemplate
     * @throws PHPLogicalDSLException
     */
    public function load($script, $params)
    {
        if (!$params instanceof ParameterTemplate) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41001], 41001);
        }
        $params->check();
        $this->rule       = $script;
        $this->input      = $params->input;
        $this->inputRange = $params->inputRange;
        $this->output     = $params->output;
        $this->parser();

    }

    /**
     *  根据传入参数,执行一个规则组判断
     *
     * @param object $params
     */
    public function execute($params)
    {
        if (!$params instanceof ParameterTemplate) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41001], 41001);
        }
        return $this->output;
    }

    /**
     * 返回解析好的语法树
     */
    public function parser()
    {
        if (is_null($this->ruleParsed)) {
            $this->ruleIsSet();
            $this->ruleParsed = Parser::getInstance()->parse($this->rule);
        }
        return $this->ruleParsed;
    }

    /**
     * 语法检查
     */
    public function inspect($throw = true)
    {
        $this->ruleIsSet();

        $error = [];
        if ($throw) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41003], 41003);
        }
        return $error;
    }

    /**
     * 链式创建DSL属性结构
     *
     * @return mixed|null|static
     */
    public function builder()
    {
        return Builder::getInstance();
    }

    /**
     * 将树形结构重新转换为脚本
     */
    public function showText()
    {

    }

    /**
     *
     */
    public function format()
    {
        return $this->showText();
    }

    /**
     * 确保规则原文已经被load
     *
     * @throws PHPLogicalDSLException
     */
    public function ruleIsSet()
    {
        if (is_null($this->rule)) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41002], 41002);
        }
    }
}