<?php

/**
 * 入口文件(index.php)中的 $config 变量收到配置参数后, 再传给框架的核心类(ac.php)
 * @author 阿川 <ahuan@achuan.io>
 * 2019-02-21 19:33
 */

// 数据库配置信息
$config['DNS_DB']['host'] = 'localhost';
$config['USER_DB']['username'] = 'achuan';
$config['PWD_DB']['password'] = '200529';
$config['PJT_DB']['dbname'] = 'project';

// 默认的控制器和操作名
$config['defaultController'] = "Item";
// 操作名
$config['defaultAction'] = "index";

return $config;