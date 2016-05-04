<?php

include 'include/SimplePDO.php';

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPasswd = '123456';
$dbName = 'test';

$db = SimplePDO::getInstance($dbHost, $dbUser, $dbPasswd, $dbName);


/* insert */
$data = ['username' => 'hello killty', 'age' => '5'];
//$db->insert('users', $data);


/* batch insert */
$multiData = [];
for($i= 0; $i < 10000; $i++) {
$multiData[] = ['username' => "hello 'skillty\"", 'age' => $i];
}

// add fail data
//$multiData[] = ['username' => 'hello killty', 'age' => 'abc'];

$db->batchInsert('users', $multiData);

