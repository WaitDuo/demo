<?php
namespace app\admin\controller;

class Index
{
    public function Index()
    {
        $db = \core\lib\MyPDo::getInstance();
        $sql = 'select * from User where 1=1';
        $data = $db->query($sql);
        p_r($data);
    }
}