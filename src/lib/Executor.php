<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170614 14:27
 * fileName :   Executor.php
 */

namespace PHPLogicalDSL\lib;

use PHPLogicalDSL\DSLStructure;
use PHPLogicalDSL\PHPLogicalDSLException;

class Executor extends SingletonInstance
{
    /**
     * 依据规则引擎,对实时进行一次判断
     *
     * @param $rulesParsed
     * @param $input
     * @return array
     */
    public function execute($rulesParsed, $input)
    {
        $output = [];
        //检查优先级,如果存在优先级,先进行规则排序
        $rulesParsed = $this->sortRulesByPriority($rulesParsed);
        foreach ($rulesParsed['rules'] as $rule) {
            //判断能否如果能通过全体条件
            //若通过则执行THEN结果
            if ($this->assertOneRule($rule['body']['WHEN'], $input)) {
                $output = $rule['body']['THEN'];
                break;
            }
        }
        return $output;
    }

    /**
     * 针对一条规则进行断言
     *
     * @param $rule
     * @param $input
     * @return bool
     */
    public function assertOneRule($rule, $input)
    {
        $return       = TRUE;
        $executeStack = [];
        while (!empty($rule)) {
            $item         = array_pop($rule);
            $whenOperator = array_keys(DSLStructure::WHEN_OPERATOR);
            if (in_array($item, $whenOperator)) {
                $operator = $item;
                $left     = array_pop($executeStack);
                $right    = array_pop($executeStack);
                $assert   = $this->calculate($left, $operator, $right);
                array_push($executeStack, $assert);
            } else {
                //req开头的参数
                if (strstr($item, 'req.') != false) {
                    $item = $this->getParamsFrInput($item, $input);
                }
                array_push($executeStack, $item);
            }
        }
        $return = array_pop($executeStack);

        return $return;
    }

    /**
     * 对规则按照优先级进行排序,优先级高的在前面
     *
     * @param $rulesParsed  规则
     * @return mixed
     */
    public function sortRulesByPriority($rulesParsed)
    {
        $rules      = [];
        $needToSort = FALSE;
        $priority   = [];
        foreach ($rulesParsed['rules'] as $key => $item) {
            $priority[$key] = $item['body']['PRIORITY'];
            if ($item['body']['PRIORITY'] > 0) {
                $needToSort = TRUE;
            }
        }
        if ($needToSort) {
            arsort($priority);
            foreach ($priority as $key => $item) {
                $rules[] = $rulesParsed['rules'][$key];
            }
        }
        $rulesParsed['rules'] = $rules;
        return $rulesParsed;

    }

    /**
     * 根据操作符计算
     *
     * @param string $left     左值
     * @param string $operator 操作符
     * @param string $right    右值
     * @return bool|float|int|null
     * @throws PHPLogicalDSLException
     */
    public function calculate($left, $operator, $right)
    {
        $return = null;
        switch ($operator) {
            case '*' :
                $return = $left * $right;
                break;
            case '/' :
                $return = $left / $right;
                break;
            case '%' :
                $return = $left % $right;
                break;
            case '+' :
                $return = $left + $right;
                break;
            case '-' :
                $return = $left - $right;
                break;
            case '>' :
                $return = $left > $right;
                break;
            case '>=' :
                $return = $left >= $right;
                break;
            case '<' :
                $return = $left < $right;
                break;
            case '<=' :
                $return = $left <= $right;
                break;
            case 'IN' :
                $return = in_array($left, $this->transSetString($right));
                break;
            case 'NOTIN' :
                $return = !in_array($left, $this->transSetString($right));
                break;
            case '=' :
                $return = $left == $right;
                break;
            case '!=' :
                $return = $left != $right;
                break;
            case 'AND' :
                $return = $left && $right;
                break;
            case 'OR' :
                $return = $left || $right;
                break;
            default:
                throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41006], 41006);

        }
        return $return;
    }

    /**
     * 处理IN 和NOT IN语句中的集合
     *
     * @param $string
     * @return array
     */
    public function transSetString($string)
    {
        $return = [];
        $return = explode(',', trim($string, '() '));
        foreach ($return as $key => $item) {
            $return[$key] = trim($item, '\'"');
        }
        return $return;
    }

    /**
     * 根据parser过的规则中的key,从输入参数中获取数据
     *
     * @param $key
     * @param $input
     * @return null
     * @throws PHPLogicalDSLException
     */
    public function getParamsFrInput($key, $input)
    {
        $return = null;
        if (strstr($key, 'req.') == false) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41007], 41007);
        }
        $key = explode('.', $key);
        unset($key[0]);
        $key    = array_reverse($key);
        $value  = $input;
        $tmpKey = null;
        while ($tmpKey = array_pop($key)) {
            if (isset($value[$tmpKey])) {
                $value = $value[$tmpKey];
            } else {
                throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41009], 41009);
            }
        }
        $return = $value;
        return $return;
    }
}