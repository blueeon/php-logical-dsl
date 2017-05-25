<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 22:23
 * fileName :   PHPLogicalDSLTest.php
 */
namespace PHPLogicalDSLTests\unit;

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
     * @param string            $script
     * @param ParameterTemplate $params
     * @dataProvider addLoadData
     */
    public function testLoad($script, $params)
    {

    }

    public function addLoadData()
    {
        $dslDataPath = __DIR__ . '/../data';
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
            ['simple1.dsl', $obj],
        ];
        closedir($handler);
        return $return;
    }
}