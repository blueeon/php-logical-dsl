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
     * @param array             $rulesParsed 解析后的规则
     * @param ParameterTemplate $params      参数对象
     * @return ParameterTemplate
     * @throws PHPLogicalDSLException
     */
    public function execute($rulesParsed, $params)
    {
        $output = [];
        if (!$params instanceof ParameterTemplate) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41001], 41001);
        }
        //检查优先级,如果存在优先级,先进行规则排序
        $rulesParsed = $this->sortRulesByPriority($rulesParsed);
        $found = FALSE;
        foreach ($rulesParsed['rules'] as $rule) {
            //判断能否如果能通过全体条件
            //若通过则执行THEN结果
            if ($this->assertOneRule($rule['body']['WHEN'], $params->getInput())) {
                $this->calThen($rule['body']['THEN'], $params);
                $found = TRUE;
                break;
            }
        }
        if (!$found) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41012], 41012);
        }
        return $params;
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
     *
     *
     * @param array  $then
     * @param object $params
     * @return object
     */
    public function calThen($then, &$params)
    {
        $totalWeight = 0;
        $weight      = [];
        foreach ($then as $key => $item) {
            if (isset($item['WEIGHT'])) {
                $totalWeight += $item['WEIGHT'];
            } else {
                $item['WEIGHT'] = 0;
            }
            $weight[$key] = $item['WEIGHT'];
        }
        $randWeight = rand(0, $totalWeight - 1);
        $start      = 0;
        foreach ($weight as $key => $item) {
            $end = $start + $item;
            if ($randWeight >= $start && $randWeight < $end) {
                foreach ($then[$key]['RESULT'] as $valueName => $value) {
                    $this->setParams($params, $valueName, $value);
                }
                break;
            }
            $start = $end;
        }
        return $params;
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
            $rulesParsed['rules'] = $rules;
        }
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
                throw new PHPLogicalDSLException("Can not find value '{$tmpKey}' in params :" . json_encode($value), 41009);
            }
        }
        $return = $value;
        return $return;
    }

    /**
     * 根据key设置输出值
     *
     * @param ParameterTemplate $params
     * @param string            $key
     * @param string            $value
     * @return ParameterTemplate
     * @throws PHPLogicalDSLException
     */
    public function setParams(&$params, $key, $value)
    {
        if (strstr($key, 'res.') == false) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41007], 41007);
        }
        $key = str_replace('res.', '', $key);
        if (count($key) > 1) {
            throw new PHPLogicalDSLException(DSLStructure::ERROR_CODE[41010], 41010);
        }
        $params->setOutput($key, $value);
        return $params;
    }
}