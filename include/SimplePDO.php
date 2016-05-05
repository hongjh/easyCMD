<?php

/**
 * 简单PDO操作类
 * @author hongjh <565983236@qq.com>
 */
class SimplePDO
{

    protected static $_instance = null;
    protected $dsn;
    protected $dbh;

    private function __construct($dbHost, $dbUser, $dbPasswd, $dbName, $dbCharset, $dbPort)
    {
        try {
            $this->dsn = 'mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbName;
            $this->dbh = new PDO($this->dsn, $dbUser, $dbPasswd);
            // 禁用php模拟预处理
            $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->dbh->exec("set names utf8");
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function getInstance($dbHost, $dbUser, $dbPasswd, $dbName, $dbCharset = 'utf8', $dbPort = '3306')
    {
        if (self::$_instance === null) {
            self::$_instance = new self($dbHost, $dbUser, $dbPasswd, $dbName, $dbCharset, $dbPort);
        }
        return self::$_instance;
    }

    public function insert($table, $data)
    {

        $fields = array_keys($data);
        $fields_values = array_values($data);

        $fields_str = implode(",", $fields);
        $fields_place_holder = implode(',', array_fill(0, count($fields), '?'));

        $sql = "INSERT INTO $table (" . $fields_str . ") VALUES (" . $fields_place_holder . ")";

        $sth = $this->dbh->prepare($sql);        
        return $sth->execute($fields_values);
    }

    public function batchInsert($table, $multiData)
    {
        if (!empty($multiData[0]) && is_array($multiData[0])) {

            $fields = array_keys($multiData[0]);
            $fields_str = implode(",", $fields);
            $fields_place_holder = implode(',', array_fill(0, count($fields), '?'));

        } else {
            throw new Exception('PDO multiInsert multiData[0] ERROR');
        }

        $sql = "INSERT INTO $table (" . $fields_str . ") VALUES (" . $fields_place_holder . ")";

        $sth = $this->dbh->prepare($sql);

        $execute_return = true;

        try {
            $this->beginTransaction();

            foreach ($multiData as $data) {
                $fields_values = array_values($data);
                // when fail return false
                $sth_execute = $sth->execute($fields_values);
                if ($sth_execute == false) {
                    throw new Exception('batchInsert fail');
                }
            }

            $this->commit();
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->rollBack();
        }

        return $this;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * beginTransaction 事务开始
     */
    public function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    /**
     * commit 事务提交
     */
    public function commit()
    {
        $this->dbh->commit();
    }

    /**
     * rollback 事务回滚
     */
    public function rollback()
    {
        $this->dbh->rollback();
    }


    /**
     * fetch
     */
    public function fetch($table, $fields = '*')
    {
        // 非数组转换
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        $sth = $this->dbh->prepare("SELECT $fields FROM $table");
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * fetchAll
     */
    public function fetchAll($table, $fields = '*')
    {
        // 非数组转换
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        $sth = $this->dbh->prepare("SELECT $fields FROM $table");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
