<?php

/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170522 19:24
 * fileName :   DSLStructure.php
 */
namespace PHPLogicalDSL;

/**
 * Class DSLKeyword DSL结构定义
 *
 * @package PHPLogicalDSL
 * @author  YangKe <yangke@xiaomi.com>
 */
class DSLStructure
{
    /**
     * DSL keywords,reserved words
     */
    const KEY_WORDS = [
        'WHEN',
        'THEN',
        'PRIORITY',
        //Logical symbol
        'AND',
        'OR',
        'IN',
        'NOT'
    ];
    /**
     * WHEN语句的操作符和其优先级
     *
     * @see http://baike.baidu.com/item/%E8%BF%90%E7%AE%97%E7%AC%A6%E4%BC%98%E5%85%88%E7%BA%A7
     */
    const WHEN_OPERATOR = [
        '*'     => 4,
        '/'     => 4,
        '%'     => 4,
        '+'     => 5,
        '-'     => 5,
        '>'     => 6,
        '>='    => 6,
        '<'     => 6,
        '<='    => 6,
        'IN'    => 6,
        'NOTIN' => 6,
        '='     => 7,
        '!='    => 7,
        'AND'   => 11,
        'OR'    => 12,


    ];
    const ERROR_CODE = [
        '41001' => 'Parameter is not legal.',
        '41002' => '$this->rule can not be null',
        '41003' => 'Rule script syntax error.',
        '41004' => 'Parameter can not be null',
        '41005' => 'Parameter must be array',
        '41006' => 'Operator is not support.',
        '41007' => 'Input must be begin with req.',
        '41008' => 'Input params can only contain three layers.',
        '41009' => 'Can not find value in Input params',
    ];
}