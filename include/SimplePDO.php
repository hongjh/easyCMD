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
        $fields_values_str = implode(',', array_fill(0, count($fields), '?'));

        $sql = "INSERT INTO $table (" . $fields_str . ") VALUES (" . $fields_values_str . ")";

        $sth = $this->dbh->prepare($sql);        
        return $sth->execute($fields_values);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

}
