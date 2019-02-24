<?php

/*
 * @Description: 项目配置文件 
 * @author: 阿川 ahuan@achuan.io
 * @Date: 2019-02-21 19:01:01
 * @LastEditTime: 2019-02-24 16:42:42
 */

// 数据库配置信息
$config['db']['host'] = 'localhost';
$config['db']['username'] = 'achuan';
$config['db']['password'] = '200529';
$config['db']['dbname'] = 'project';

// 默认的控制器和操作名
$config['defaultController'] = "Item";
// 操作名
$config['defaultAction'] = "index";

return $config;