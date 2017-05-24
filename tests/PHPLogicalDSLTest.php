<?php

/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 22:23
 * fileName :   PHPLogicalDSLTest.php
 */

/**
 * Class PHPLogicalDSLTest
 *
 * @author  YangKe <yangke@xiaomi.com>
 * @test
 */
class PHPLogicalDSLTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param                            $script
     * @param \PHPLogicalDSL\lib\Request $request
     * @param \PHPLogicalDSL\lib\Result  $result
     * @dataProvider addLoadData
     */
    public function testLoad($script, \PHPLogicalDSL\lib\Request $request, \PHPLogicalDSL\lib\Result $result)
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack) - 1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }

    public function addLoadData()
    {
        $dslDataPath = './data';

        return [

        ];
    }
}