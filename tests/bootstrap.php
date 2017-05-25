<?php
/**
 * author:      YangKe <yangke@xiaomi.com>
 * createTIme:  20170523 20:33
 * fileName :   bootstrap.php
 */
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}
define('PHPUNIT_COMPOSER_INSTALL', __DIR__.'/../vendor/autoload.php');
require PHPUNIT_COMPOSER_INSTALL;
