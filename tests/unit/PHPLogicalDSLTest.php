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

    protected function setUp()
    {
    }

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
        var_dump($obj->parser());
        var_dump($obj->format());
        return $obj;
    }

    /**
     *  根据传入参数,执行一个规则组判断
     *
     * @param object $params 参数对象
     * @param object $obj    实例化的PHPLogicalDSL对象
     * @dataProvider addExecuteData
     */
    public function testExecute($params, $obj)
    {
        $res = $obj->execute($params);
        $this->assertEquals($res, array('express_id' => null));
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
            'simple1.dsl' => [$scripts['simple1.dsl'], $obj],
        ];

        return $return;
    }

    /**
     * 返回基准数据
     *
     * @param $script
     * @param $params
     * @return array
     */
    public function addExecuteData()
    {
        $return = [];
        $ret    = $this->addLoadData();
        foreach ($ret as $key => $item) {
            $obj    = new PHPLogicalDSL();
            $params = $item[1];
            $script = $item[0];
            $obj->load($script, $params);
            $return[$key] = [$params, $obj];
        }
        return $return;
    }
}