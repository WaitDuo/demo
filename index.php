<?php
/* 
 * @author duoduo
 * @date 2017-08-04 
 * ����ļ�
 * 1.���峣��
 * 2.���غ�����
 * 3.�������
 */
define('BASE_PATH',str_replace('\\', '/', __DIR__));//������վ��Ŀ¼
define('CORE_PATH', BASE_PATH.'/core');//�����ܺ����ļ�Ŀ¼
define('APP_PATH', BASE_PATH.'/app');//����appĿ¼
define('APP_NAME', '\app');
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('DEBUG', true);//���󱨸��Ƿ���
if(DEBUG){
    ini_set('display_error', 'On');
}else{
    ini_set('display_error', 'Off');
}
include (CORE_PATH.'/common/function.php');//���뺯����
include (CORE_PATH.'/Frame.php');//�����ܺ����ļ�
spl_autoload_register('\core\Frame::load');//�Զ�������
\core\Frame::run();//�������

