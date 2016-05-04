<?php
include 'include/SimplePDO.php';

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPasswd = '123456';
$dbName = 'test';

$db = SimplePDO::getInstance($dbHost, $dbUser, $dbPasswd, $dbName);


/* insert */
$data = array(
    'username' => 'hello killty',
    'age'=> '5'
);
$db->insert('users', $data);
/* end insert */