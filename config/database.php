<?php

/**
 * 项目配置文件
 * @author: 阿川 <achuan@achuan.io>
 * @Time: 2019/2/21 19:01:01
 */

// 数据库配置信息
$config['db']['host'] = '127.0.0.1';
$config['db']['username'] = 'root';
$config['db']['password'] = 'achuan0529';
$config['db']['dbname'] = 'project';

// 默认的控制器和操作名
$config['defaultController'] = "Item";
// 操作名
$config['defaultAction'] = "index";

return $config;