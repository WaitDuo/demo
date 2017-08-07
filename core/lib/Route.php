<?php
/*  
 *@author duoduo
 *@date 2017-08-04
 * 
 */
namespace core\lib;

class Route
{
    public $module;
    public $controller;
    public $action;
    public function __construct()
    {
        //$_SERVER[''];
        /* xxx.com/index.php/index/index
         *1.隐藏index.php
         *2.获取URL参数
         *3.返回对应的控制器和方法
         */
       //p_r($_SERVER);
       if($_SERVER['REQUEST_URI']!='/' && isset($_SERVER['REQUEST_URI'])){
            $path    = $_SERVER['REQUEST_URI'];
            $patharr = explode('/', trim($path, '/'));
            if(isset($patharr['0'])){
                $this->module = $patharr['0'];
                unset($patharr['0']);
            }else{
                $this->module = \core\lib\Config::get('default_module', 'route');
            } 
            if(isset($patharr['1'])){
                $this->controller = $patharr['1'];
                unset($patharr['1']);
            }else{
                $this->controller = \core\lib\Config::get('default_controller', 'route');
            }
            if(isset($patharr['2'])){
                $this->action = $patharr['2'];
                unset($patharr['2']);
            }else{
                $this->action = \core\lib\Config::get('default_controller', 'route');
            }
       }else{
           $this->module = 'index';
           $this->controller = 'index';
           $this->action = 'index';
       }
       //url 多余部分 /id/2/str/3
       if(!empty($patharr)){
           $count = count($patharr)+3;
           $i     = 3;
           while($i<$count){
               if(isset($patharr[$i+1])){
                   $_REQUEST[$patharr[$i]] = $patharr[$i+1];
               }
               $i = $i+2;
           }
       }
    }







}