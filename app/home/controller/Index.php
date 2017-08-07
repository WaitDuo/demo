<?php
namespace app\home\controller;

class Index
{
    public function index()
    {
        $config = \core\lib\Config::getArr('datebase');
        $localhost = \core\lib\Config::get('kkk', 'datebase');
        $localhost = \core\lib\Config::get('localhost', 'datebase');
        p_r($config);
        p_r($localhost);
        //echo $username.'<br/>';
        //echo $localhost.'<br/>';
        //echo "this is home index index";
    }
    public function add()
    {
        echo "this is add";
    }
}