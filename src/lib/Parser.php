<?php
namespace PHPLogicalDSL\lib;

use PHPLogicalDSL\DSLStructure;

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
        $parsed = [
            'annotation' => '',
            'rules'      => [],
        ];
        list($parsed['annotation'], $script) = $this->removeAnnotation($script);
        $scripts = $this->splitRule($script);
        foreach ($scripts as $item) {
            $itemParsed        = $this->parseOneRule($item);
            $parsed['rules'][] = $itemParsed;
        }
        return $parsed;
    }

    /**
     * 去除注释DSL
     *
     * @param $script
     * @return mixed
     */
    public function removeAnnotation($script)
    {
        $pattern = "/\/\*.*?\*\//s";
        preg_match($pattern, $script, $annotation);
        return [$annotation[0], preg_replace($pattern, '', $script)];
    }

    /**
     * 切割规则
     *
     * @param $script
     * @return array
     */
    public function splitRule($script)
    {
        $scripts = [];
        //大括号前有空格去掉
        $pattern = '/(\s+\{)/';
        $script  = trim(preg_replace($pattern, '{', $script));
        //所有括号前后加空格
        $pattern = '/(\()/';
        $script  = trim(preg_replace($pattern, ' ( ', $script));
        $pattern = '/(\))/';
        $script  = trim(preg_replace($pattern, ' ) ', $script));
        //去除换行符,和多个空白符
        $pattern = '/\s+/';
        $script  = trim(preg_replace($pattern, ' ', $script));
        //依据大括号切割
        $pattern = '/([^\s|\}|\{]*)' . '(\{)' . '([^\}|\{]*)' . '(\})/i';
        preg_match_all($pattern, $script, $scripts);
        return $scripts[0];
    }

    /**
     * 解析一条规则为规则树
     *
     * @param $script
     * @return array
     */
    public function parseOneRule($script)
    {
        $return = [
            'rule_name' => '',
            'body'      => [
                'WHEN' => [],
                'THEN' => [],
            ],
        ];
        //按照{}切割出rule name 和 rule正文
        $script              = preg_split("/(\}|\{)/", $script);
        $return['rule_name'] = trim($script[0]);
        //切割出WHEN和THEN语句
        $script = preg_split("/[\s]*(WHEN|THEN)[\s]+/i", trim($script[1]));
        //拆分when语句中的条件子句
        $whenStr                = $script[1];
        $return['body']['WHEN'] = $this->parseWhen($whenStr);
        //拆分then语句中的结果子句
        $thenStr                = $script[2];
        $return['body']['THEN'] = $this->parseThen($thenStr);
        return $return;
    }

    /**
     * 将WHEN语句解析为前缀表达式
     *
     * @param $whenScript
     * @return array
     */
    public function parseWhen($whenScript)
    {
        $return = [];
        //处理 IN 和NOT IN语句之后的括号内容,去掉内部的空格
        $pattern = '/IN\s*' . '(\()' . '[^\)]*' . '(\))' . '/i';
        preg_match_all($pattern, $whenScript, $matches);
        $inFrom = [];
        $inTo   = [];
        foreach ($matches[0] as $match) {
            $inFrom[] = $match;

            $inTo[] = str_ireplace('IN(', 'IN (', str_replace(' ', '', $match));
        }
        $whenScript = str_ireplace($inFrom, $inTo, $whenScript);
        //替换NOT IN
        $whenScript = str_ireplace('NOT IN', 'NOTIN', $whenScript);

        $whenScriptList = explode(' ', $whenScript);
        $return         = $this->parseWhenToPrefixExpression($whenScriptList);
        return $return;
    }

    /**
     * 根据传入的中缀表达式列表,返回前缀表达式栈
     *
     * @param array $whenScript
     * @return array
     */
    public function parseWhenToPrefixExpression(array $whenScript)
    {
        $operator = array_keys(DSLStructure::WHEN_OPERATOR);
        $S1       = [];//数值、中间结果栈
        $S2       = [];//运算符栈
        foreach (array_reverse($whenScript) as $item) {

            //运算符
            if (!is_array($item) && in_array(strtoupper(trim($item)), $operator)) {
                $item = strtoupper(trim($item));
                while (1) {
                    if (empty($S1) || end($S1) == ')') {
                        array_push($S1, $item);
                        break;
                    } elseif (DSLStructure::WHEN_OPERATOR[$item] <= DSLStructure::WHEN_OPERATOR[end($S1)]) {
                        array_push($S1, $item);
                        break;
                    } else {
                        array_push($S2, array_pop($S1));
                    }
                }
            } //遇到括号
            elseif (!is_array($item) && in_array(trim($item), ['(', ')'])) {
                if ($item == ')') {
                    array_push($S1, $item);
                } else {
                    while (1) {
                        $S1Op = array_pop($S1);
                        if ($S1Op == ')') {
                            break;
                        }
                        array_push($S2, $S1Op);
                    }
                }
            } //数值直接入栈
            else {
                array_push($S2, $item);
            }
        }
        //将$1中剩余操作符依次弹出并压入$2
        while (!empty($S1)) {
            array_push($S2, array_pop($S1));
        }
        $return = array_reverse($S2);
        return $return;
    }

    public function parseThen($thenScript)
    {
        $return = [];

        $return = [
            [
                'WEIGHT' => 30,
                'RESULT' => [
                    'res.mihome' => 100,
                    'res.price'  => 100,
                ],
            ],
            [
                'WEIGHT' => 70,
                'RESULT' => [
                    'res.mihome' => 112,
                    'res.price'  => 100,
                ],
            ]
        ];
        var_dump($thenScript);
        //如果THEN语句是单条结果,添加权重100
        $pattern = '/(\()[^\)]+(\))/i';
        preg_match_all($pattern, $thenScript, $subThen);
        $subThen = $subThen[0];
        if (empty($subThen[0])) {
            $subThen[] = "({$thenScript} AND WEIGHT=100)";
        }
        foreach ($subThen as $item) {
            $returnItem = [];
            $item       = trim($item, '()');
            $item       = explode('AND', $item);
            foreach ($item as $subItem) {
                $subItem = explode('=', $subItem);
                if (strtoupper(trim($subItem[0])) == 'WEIGHT') {
                    $returnItem['WEIGHT'] = trim($subItem[1]);
                } else {
                    $returnItem['RESULT'][trim($subItem[0])] = trim($subItem[1]);
                }

            }
            $return[] = $returnItem;
        }
        var_dump($return);
        exit;
        return $return;
    }

    /**
     * 将解析过的语法树,转回文本
     *
     * @param $parsed
     */
    public function showText($parsed)
    {
        var_dump($parsed);
        $script = $parsed['annotation'] . "\n";
        foreach ($parsed['rules'] as $item) {

        }
        exit;
    }

    /**
     * 从前缀表达式转换回中缀表达式
     *
     * @TODO
     * @param $parsed
     */
    public function prefixToInfix($parsed)
    {

    }
}