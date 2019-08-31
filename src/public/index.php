<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
error_reporting(E_ALL);
ini_set('display_errors',1);
define('APP_PATH', __DIR__ . '/../app/');
// 定义配置文件目录
define('CONF_PATH', __DIR__ . '/../config/');
// 定义缓存目录
define('RUNTIME_PATH', __DIR__ . '/../runtime/');

// 加载框架引导文件
require __DIR__ . '/../tp/start.php';
