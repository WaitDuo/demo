<?php
namespace core\lib;

class MyPDo
{
    /**
     * MyPDO
     * 
     * @author duoduo 
     * 
     * 
     */
    protected static $_instance = null;

    protected $dbName = '';

    protected $dsn;

    protected $dbh;

    /**
     * ����
     *
     * @return MyPDO
     */
    private function __construct()
    { 
        $database = Config::getArr('database');
        try {
            $this->dsn = 'mysql:host=' . $database['host'] . ';dbname=' . $database['dbname'];
            $this->dbh = new \PDO($this->dsn, $database['username'], $database['password']);
            $this->dbh->exec('SET character_set_connection=' . $database['charset'] . ', character_set_results=' . $database['charset'] . ', character_set_client=binary');
        } catch (\PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }

    /**
     * ��ֹ��¡
     */
    private function __clone()
    {}

    /**
     * Singleton instance
     *
     * @return Object
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Query ��ѯ
     *
     * @param String $strSql
     *            SQL���
     * @param String $queryMode
     *            ��ѯ��ʽ(All or Row)
     * @param Boolean $debug            
     * @return Array
     */
    public function query($strSql, $queryMode = 'All', $debug = false)
    {
        if ($debug === true)
            $this->debug($strSql);
        $recordset = $this->dbh->query($strSql);
        $this->getPDOError();
        if ($recordset) {
            $recordset->setFetchMode(\PDO::FETCH_ASSOC);
            if ($queryMode == 'All') {
                $result = $recordset->fetchAll();
            } elseif ($queryMode == 'Row') {
                $result = $recordset->fetch();
            }
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * Update ����
     *
     * @param String $table
     *            ����
     * @param Array $arrayDataValue
     *            �ֶ���ֵ
     * @param String $where
     *            ����
     * @param Boolean $debug            
     * @return Int
     */
    public function update($table, $arrayDataValue, $where = '', $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        if ($where) {
            $strSql = '';
            foreach ($arrayDataValue as $key => $value) {
                $strSql .= ", `$key`='$value'";
            }
            $strSql = substr($strSql, 1);
            $strSql = "UPDATE `$table` SET $strSql WHERE $where";
        } else {
            $strSql = "REPLACE INTO `$table` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        }
        if ($debug === true)
            $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * Insert ����
     *
     * @param String $table
     *            ����
     * @param Array $arrayDataValue
     *            �ֶ���ֵ
     * @param Boolean $debug            
     * @return Int
     */
    public function insert($table, $arrayDataValue, $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "INSERT INTO `$table` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        if ($debug === true)
            $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * Replace ���Ƿ�ʽ����
     *
     * @param String $table
     *            ����
     * @param Array $arrayDataValue
     *            �ֶ���ֵ
     * @param Boolean $debug            
     * @return Int
     */
    public function replace($table, $arrayDataValue, $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "REPLACE INTO `$table`(`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        if ($debug === true)
            $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * Delete ɾ��
     *
     * @param String $table
     *            ����
     * @param String $where
     *            ����
     * @param Boolean $debug            
     * @return Int
     */
    public function delete($table, $where = '', $debug = false)
    {
        if ($where == '') {
            $this->outputError("'WHERE' is Null");
        } else {
            $strSql = "DELETE FROM `$table` WHERE $where";
            if ($debug === true)
                $this->debug($strSql);
            $result = $this->dbh->exec($strSql);
            $this->getPDOError();
            return $result;
        }
    }

    /**
     * execSql ִ��SQL���
     *
     * @param String $strSql            
     * @param Boolean $debug            
     * @return Int
     */
    public function execSql($strSql, $debug = false)
    {
        if ($debug === true)
            $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * ��ȡ�ֶ����ֵ
     *
     * @param string $table
     *            ����
     * @param string $field_name
     *            �ֶ���
     * @param string $where
     *            ����
     */
    public function getMaxValue($table, $field_name, $where = '', $debug = false)
    {
        $strSql = "SELECT MAX(" . $field_name . ") AS MAX_VALUE FROM $table";
        if ($where != '')
            $strSql .= " WHERE $where";
        if ($debug === true)
            $this->debug($strSql);
        $arrTemp = $this->query($strSql, 'Row');
        $maxValue = $arrTemp["MAX_VALUE"];
        if ($maxValue == "" || $maxValue == null) {
            $maxValue = 0;
        }
        return $maxValue;
    }

    /**
     * ��ȡָ���е�����
     *
     * @param string $table            
     * @param string $field_name            
     * @param string $where            
     * @param bool $debug            
     * @return int
     */
    public function getCount($table, $field_name, $where = '', $debug = false)
    {
        $strSql = "SELECT COUNT($field_name) AS NUM FROM $table";
        if ($where != '')
            $strSql .= " WHERE $where";
        if ($debug === true)
            $this->debug($strSql);
        $arrTemp = $this->query($strSql, 'Row');
        return $arrTemp['NUM'];
    }

    /**
     * ��ȡ������
     *
     * @param String $dbName
     *            ����
     * @param String $tableName
     *            ����
     * @param Boolean $debug            
     * @return String
     */
    public function getTableEngine($dbName, $tableName)
    {
        $strSql = "SHOW TABLE STATUS FROM $dbName WHERE Name='" . $tableName . "'";
        $arrayTableInfo = $this->query($strSql);
        $this->getPDOError();
        return $arrayTableInfo[0]['Engine'];
    }

    /**
     * beginTransaction ����ʼ
     */
    private function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    /**
     * commit �����ύ
     */
    private function commit()
    {
        $this->dbh->commit();
    }

    /**
     * rollback ����ع�
     */
    private function rollback()
    {
        $this->dbh->rollback();
    }

    /**
     * transaction ͨ�����������SQL���
     * ����ǰ��ͨ��getTableEngine�жϱ������Ƿ�֧������
     *
     * @param array $arraySql            
     * @return Boolean
     */
    public function execTransaction($arraySql)
    {
        $retval = 1;
        $this->beginTransaction();
        foreach ($arraySql as $strSql) {
            if ($this->execSql($strSql) == 0)
                $retval = 0;
        }
        if ($retval == 0) {
            $this->rollback();
            return false;
        } else {
            $this->commit();
            return true;
        }
    }

    /**
     * checkFields ���ָ���ֶ��Ƿ���ָ�����ݱ��д���
     *
     * @param String $table            
     * @param array $arrayField            
     */
    private function checkFields($table, $arrayFields)
    {
        $fields = $this->getFields($table);
        foreach ($arrayFields as $key => $value) {
            if (! in_array($key, $fields)) {
                $this->outputError("Unknown column `$key` in field list.");
            }
        }
    }

    /**
     * getFields ��ȡָ�����ݱ��е�ȫ���ֶ���
     *
     * @param String $table
     *            ����
     * @return array
     */
    private function getFields($table)
    {
        $fields = array();
        $recordset = $this->dbh->query("SHOW COLUMNS FROM $table");
        $this->getPDOError();
        $recordset->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }
    
    /**
     * getPDOError ����PDO������Ϣ
     */
    private function getPDOError()
    {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorInfo();
            $this->outputError($arrayError[2]);
        }
    }

    /**
     * debug
     *
     * @param mixed $debuginfo            
     */
    private function debug($debuginfo)
    {
        var_dump($debuginfo);
        exit();
    }

    /**
     * ���������Ϣ
     *
     * @param String $strErrMsg            
     */
    private function outputError($strErrMsg)
    {
        throw new \Exception('MySQL Error: ' . $strErrMsg);
    }

    /**
     * destruct �ر����ݿ�����
     */
    public function destruct()
    {
        $this->dbh = null;
    }
}