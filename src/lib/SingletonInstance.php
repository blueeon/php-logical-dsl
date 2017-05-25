<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170522 19:24
 * fileName :   SingletonInstance.php
 */

namespace PHPLogicalDSL\lib;

/**
 * Class SingletonInstance    简单单例class基类
 *
 * @package PHPLogicalDSL\lib
 * @author  YangKe <yangke@xiaomi.com>
 */
abstract class SingletonInstance
{

    protected function __construct()
    {
    }

    /**
     * @var null 单例存放数组
     */
    protected static $instance = NULL;

    /**
     * 单例入口方法
     *
     * @static
     * @return mixed|null|static
     */
    public static function getInstance()
    {
        if (empty(static::$instance[get_called_class()])) {
            static::$instance[get_called_class()] = new static();
        }
        return static::$instance[get_called_class()];
    }

    /**
     * 禁止克隆
     *
     * @throws Exception
     *
     * @return void
     */
    final public function __clone()
    {
        throw new \Exception('Forbidden to do clone.', 500);
    }

    /**
     * 禁止串行化
     *
     * @throws Exception
     *
     * @return void
     */
    final public function __wakeup()
    {
        throw new Exception('Forbidden to do wakeup', 500);
    }
} 