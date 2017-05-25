<?php

/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 11:55
 * fileName :   PHPLogicalDSL.php
 */
namespace PHPLogicalDSL;

use PHPLogicalDSL\lib\Builder;
use PHPLogicalDSL\lib\Parser;
use PHPLogicalDSL\lib\Request;
use PHPLogicalDSL\lib\Result;


/**
 * Class PHPLogicalDSL
 *
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
    private $request = null;

    /**
     * @var null 返回结果定义
     */
    private $result = null;

    /**
     * 加载一个规则,并且完成语法检查,解析
     *
     * @param $script
     */
    public function load($script, $request, $result)
    {
        if ($request instanceof Request && $result instanceof Result) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41001], 41001);
        }
        $this->rule    = $script;
        $this->request = $request;
        $this->result  = $result;
        $this->parser();

    }

    /**
     *  根据传入参数,执行一个规则组判断
     *
     * @param Request $request
     */
    public function execute(Request $request){
        return $this->result;
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

    public function builder()
    {
        return Builder::getInstance();
    }

    public function format()
    {

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