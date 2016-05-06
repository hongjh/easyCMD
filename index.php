<?php

include 'include/SimplePDO.php';

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPasswd = '123456';
$dbName = 'test';

$db = SimplePDO::getInstance($dbHost, $dbUser, $dbPasswd, $dbName);

$t1 = microtime(true);
/* insert */
$data = ['username' => 'hello killty', 'age' => '5'];
//$db->insert('users', $data);


/* batch insert */
$multiValues = [];
for($i= 0; $i < 10000; $i++) {
    // add sunc data
    $multiValues[] = ["hello 'skillty\"", $i];
}

// add fail data
//$multiValues[] = ['hello killty', 'abc'];

$db->batchInsert('users', ['username', 'age'], $multiValues);


//$result = $db->fetch('users', ['username']);

//print_r($result);



$t2 = microtime(true);
// 花费秒数，取小数点后3位
echo round($t2-$t1,3);