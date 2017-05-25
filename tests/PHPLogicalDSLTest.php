<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 22:23
 * fileName :   PHPLogicalDSLTest.php
 */
namespace PHPLogicalDSL\tests;

use PHPLogicalDSL\lib\ParameterTemplate;
use PHPLogicalDSL\tests\data\Simple1Params;

/**
 * Class PHPLogicalDSLTest
 *
 * @package PHPLogicalDSL\tests
 * @author  YangKe <yangke@xiaomi.com>
 * @test
 */
class PHPLogicalDSLTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     *
     * @param string            $script
     * @param ParameterTemplate $request
     * @param ParameterTemplate $result
     * @dataProvider addLoadData
     */
    public function testLoad($script, $params)
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
        $handler     = opendir($dslDataPath);
        $files       = [];
        $return      = [];
        while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
            if ($filename != "." && $filename != "..") {
                $files[] = $filename;
            }
        }
        $obj        = new Simple1Params();
        $obj->input = [
            'order'   => [
                'order_id'      => null,
                'stock_channel' => null,
                'order_from'    => null,
                'price'         => null,
                'mihome'        => null,
            ],
            'address' => [
                'province' => null,
                'city'     => null,
                'area'     => null,
            ],
        ];
        $files      = [
            ['simple1.dsl',],
        ];
        closedir($handler);
        return $return;
    }
}