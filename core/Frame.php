<?php
/*  
 * @author duoduo
 * @date 2017-08-04 
 * 1.启动框架 自动加载类库
 * 
 */
namespace core;

class Frame
{
    public static $classMap = array();
    static public function run()
    {

        $route      = new \core\lib\Route();
        $module     = $route->module;
        $controller = $route->controller;
        $action     = $route->action;     
        $modulestr  = APP_PATH.'/'.$module;
        if(is_dir($modulestr)==false){
            throw new \Exception("this module is not exist:$module") ;
        }else{
            
            $controllerstr = $modulestr.'/controller/'.$controller.'.php';
            if(file_exists($controllerstr)){
                include $controllerstr;
                //app\home\controller\
                $classname = APP_NAME.'\\'.$module.'\\controller\\'.$controller;
                $contr     = new $classname;
                if(method_exists($contr,$action)){
                    $contr->$action();
                }else{
                    throw new \Exception("this action is not exist:$action");
                }
                
            }else{
                throw new \Exception("this controller is not exist:$modulestr");
            }      
       
        }

               
    }
    static public function load($class)
    {        
        //自动加载类库
        if(isset($classMap[$class])){
            return true;
        }else{   
            $class     = str_replace('\\', '/', $class); 
            $file      = BASE_PATH.'/'.$class.'.php';
            if(file_exists($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }
    }
}