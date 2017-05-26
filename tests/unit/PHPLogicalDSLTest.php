<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 22:23
 * fileName :   PHPLogicalDSLTest.php
 */
namespace PHPLogicalDSLTests\unit;

use PHPLogicalDSL\PHPLogicalDSL;
use PHPLogicalDSLTests\data\Simple1Params;
use PHPUnit\Framework\TestCase;

/**
 * Class PHPLogicalDSLTest
 *
 * @package PHPLogicalDSLTests
 * @author  YangKe <yangke@xiaomi.com>
 * @test
 */
class PHPLogicalDSLTest extends TestCase
{
    /**
     *
     *
     * @param string $script
     * @param object $params
     * @dataProvider addLoadData
     * @return array
     */
    public function testLoad($script, $params)
    {
        $obj = new PHPLogicalDSL();
        $obj->load($script, $params);
        $this->assertTrue(true);
        return [2222];
    }

    /**
     *
     * @return array
     */
    public function testParams(){
        return [1111];
    }
    /**
     *  根据传入参数,执行一个规则组判断
     *
     * @param object $params
     * @depends testParams
     */
    public function testExecute($params)
    {
        var_dump($params);
    }
    /**
     * 生成load方法测试数据
     *
     * @return array
     */
    public function addLoadData()
    {
        $dslDataPath = __DIR__ . '/../data/';
        $handler     = opendir($dslDataPath);
        $scripts     = [];
        $return      = [];
        while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
            if ($filename != "." && $filename != "..") {
                $scripts[$filename] = file_get_contents($dslDataPath . $filename);
            }
        }
        closedir($handler);
        $obj        = new Simple1Params();
        $obj->input = [
            'order'   => [
                'order_id'      => 1231245432,
                'stock_channel' => 'cn-order',
                'order_from'    => 1,
                'price'         => 1000.00,
                'mihome'        => 112,
            ],
            'address' => [
                'province' => 377,
                'city'     => 37,
                'area'     => 50,
            ],
        ];
        $return     = [
            [$scripts['simple1.dsl'], $obj],
        ];

        return $return;
    }
}