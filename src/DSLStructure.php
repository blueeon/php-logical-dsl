<?php

/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170522 19:24
 * fileName :   DSLStructure.php
 */
namespace PHPLogicalDSL\src;

/**
 * Class DSLKeyword DSL结构定义
 *
 * @package PHPLogicalDSL\src
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
        //Logical symbol
        'AND',
        'OR',
        'IN',
        'NOT'
    ];
    const ERROR_CODE = [
        '41001' => 'Parameter is not legal.',
        '41002' => '$this->rule can not be null',
        '41003' => 'Rule script syntax error.',
        '41004' => 'Parameter can not be null',
        '4100%' => 'Parameter must be array',
    ];
}