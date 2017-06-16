<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 22:23
 * fileName :   PHPLogicalDSLTest.php
 */
namespace PHPLogicalDSLTests\unit;

use PHPLogicalDSL\lib\Parser;
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
     * 测试load是否正确执行
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
        $parsed = $obj->parser();
        $this->assertTrue(!empty($parsed));
        $this->assertTrue(!empty($parsed['rules']) && count($parsed['rules']) >= 1);
        foreach ($parsed['rules'] as $rule) {
            $this->assertTrue(!empty($rule['rule_name']));
            $this->assertTrue(!empty($rule['body']));
            $this->assertTrue(!empty($rule['body']['WHEN']));
            $this->assertTrue(!empty($rule['body']['THEN']));
            $this->assertTrue(isset($rule['body']['PRIORITY']) && $rule['body']['PRIORITY'] >= 0);
        }
        $obj2 = new PHPLogicalDSL();
        $obj2->load($script, $params);

        $this->assertEquals($obj->format(), $obj2->format());
    }

    /**
     *  根据传入参数,执行一个规则组判断
     *
     * @param ParameterTemplate $params 参数对象
     * @param PHPLogicalDSL     $obj    实例化的PHPLogicalDSL对象
     * @dataProvider addExecuteData
     */
    public function testExecute($params, $obj)
    {
        $res = $obj->execute($params);
        var_dump($obj->format(), $res);
//        $this->assertEquals($res, array('express_id' => null));
    }

    /**
     * 生成load方法测试数据
     *
     * @return array
     */
    public function addLoadData()
    {
        $obj         = new Simple1Params();
        $dslDataPath = __DIR__ . '/../data/';
        $handler     = opendir($dslDataPath);
        $scripts     = [];
        $return      = [];
        while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
            if ($filename != "." && $filename != ".." && strstr($filename, '.dsl') != false) {
                $scripts[$filename] = file_get_contents($dslDataPath . $filename);
                $return[$filename]  = [
                    $scripts[$filename],
                    $obj
                ];
            }
        }
        closedir($handler);
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
            $script = $item[0];
            $params = $item[1];
            //构造规则引擎
            $obj->load($script, $params);
            //构造参数
            $paramsObj = new Simple1Params();
            $paramsObj->setInput([
                'order'   => [
                    'order_id'      => 1231245432,
                    'stock_channel' => 'cn-order',
                    'order_from'    => 11,
                    'order_type'    => 10,
                    'price'         => 1000.00,
                    'mihome'        => 112,
                ],
                'address' => [
                    'province' => 377,
                    'city'     => 37,
                    'area'     => 50,
                ],
            ]);

            $return[$key] = [$paramsObj, $obj];
        }
        return $return;
    }
}