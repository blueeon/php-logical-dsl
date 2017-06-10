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
        $parsed = [];
        echo "\n\n";
        $script  = $this->removeAnnotation($script);
        $scripts = $this->splitRule($script);
        foreach ($scripts as $item) {
            $itemParsed = $this->parseOneRule($item);
            var_dump($item, $itemParsed);
            exit;
            $parsed = $itemParsed;
        }
        echo "\n\n";
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
        return preg_replace($pattern, '', $script);
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
        //去除换行符,和多个空白符
        $pattern = '/\s+/';
        $script  = trim(preg_replace($pattern, ' ', $script));
        //依据大括号切割
        $pattern = '/([^\s|\}|\{]*)' . '(\{)' . '([^\}|\{]*)' . '(\})/i';
        preg_match_all($pattern, $script, $scripts);
        return $scripts[0];
    }

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
        $whenStr = $script[1];
        $pattern = '/[\s]+(AND|OR)[\s]+/i';
        $when    = preg_split($pattern, trim($whenStr));
        var_dump($script, $whenStr, $when);

        //拆分then语句中的结果子句
        $thenStr = $script[2];
        $pattern = '';
        return $return;
    }
}