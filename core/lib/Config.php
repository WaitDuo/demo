<?php
/* 
 * @author duoduo
 * @2017-08-05
 * 
 */
namespace core\lib;

class Config
{
    static public $configs = array();
    
    static public function get($name, $file)
    {
        /*
         * 1.判断配置文件是否存在
         * 2.判断配置是否存在
         * 3.缓存配置 
         */
        if(isset(self::$configs[$file])){
            return self::$configs[$file][$name];
        }else{
            $path = BASE_PATH.'/core/config/'.$file.'.php';
            if(file_exists($path)){
                $config = include $path;
                if(isset($config[$name])){
                    self::$configs[$file] = $config;
                    return $config[$name];
                }else{
                    throw new \Exception("config name is not exits:$file");
                }
            }else{
                throw new \Exception("config file is not exits:$file");
            }
        }
          
    }
    
    static public function getArr($file)
    {
        /*
         * 1.判断配置文件是否存在
         * 2.判断配置是否存在
         * 3.缓存配置
         */
        if(isset(self::$configs[$file])){
            return self::$configs[$file];
        }else{
            $path = BASE_PATH.'/core/config/'.$file.'.php';
            if(file_exists($path)){
                $config = include $path;
                return $config;
            }else{
                throw new \Exception("config file is not exits:$file");
            }
        }
    
    }
    
    
}