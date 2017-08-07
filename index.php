<?php
/* 
 * @author duoduo
 * @date 2017-08-04 
 * 入口文件
 * 1.定义常量
 * 2.加载函数库
 * 3.启动框架
 */
define('BASE_PATH',str_replace('\\', '/', __DIR__));//定义网站根目录
define('CORE_PATH', BASE_PATH.'/core');//定义框架核心文件目录
define('APP_PATH', BASE_PATH.'/app');//定义app目录
define('APP_NAME', '\app');
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('DEBUG', true);//错误报告是否开启
if(DEBUG){
    ini_set('display_error', 'On');
}else{
    ini_set('display_error', 'Off');
}
include (CORE_PATH.'/common/function.php');//引入函数库
include (CORE_PATH.'/Frame.php');//引入框架核心文件
spl_autoload_register('\core\Frame::load');//自动加载类
\core\Frame::run();//启动框架

