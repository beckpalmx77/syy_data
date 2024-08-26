<?php

ini_set('display_errors', 1);
error_reporting(~0);

include ("../config/connect_sqlserver.php");
include ("../config/connect_db.php");


$sql_keymax = " select ARD_KEY from ardetail order by ARD_KEY desc  limit 1  ";
$statement = $conn->query($sql_keymax);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $result) {

    $ARD_KEY_LAST = $result['ARD_KEY'];

}


$sql_sqlsvr = "select * from ardetail where ARD_KEY >= " . $ARD_KEY_LAST;

//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_sqlsvr);
//fclose($myfile);

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {


    $sql_find = "SELECT * FROM ardetail WHERE ARD_KEY = '" . $result_sqlsvr["ARD_KEY"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        $sql = "UPDATE ardetail SET ARD_AR=:ARD_AR,ARD_DI=:ARD_DI,ARD_ARCD=:ARD_ARCD        
        WHERE ARD_KEY = :ARD_KEY ";

        echo " Update ardetail : " . $result_sqlsvr["ARD_KEY"] . " | " . $result_sqlsvr["ARD_AR"] . " | " . $result_sqlsvr["ARD_ARCD"] . "\n\r";

        $query = $conn->prepare($sql);
        $query->bindParam(':ARD_AR', $result_sqlsvr["ARD_AR"], PDO::PARAM_STR);
        $query->bindParam(':ARD_DI', $result_sqlsvr["ARD_DI"], PDO::PARAM_STR);
        $query->bindParam(':ARD_ARCD', $result_sqlsvr["ARD_ARCD"], PDO::PARAM_STR);
        $query->bindParam(':ARD_KEY', $result_sqlsvr["ARD_KEY"], PDO::PARAM_STR);
        $query->execute();
    } else {

        echo " Insert ardetail : " . $result_sqlsvr["ARD_KEY"] . " | " . $result_sqlsvr["ARD_AR"] . " | " . $result_sqlsvr["ARD_ARCD"] . "\n\r";

        $sql = "INSERT INTO ardetail(ARD_KEY,ARD_AR,ARD_DI,ARD_ARCD)
        VALUES (:ARD_KEY,:ARD_AR,:ARD_DI,:ARD_ARCD)";
        $query = $conn->prepare($sql);
        $query->bindParam(':ARD_KEY', $result_sqlsvr["ARD_KEY"], PDO::PARAM_STR);
        $query->bindParam(':ARD_AR', $result_sqlsvr["ARD_AR"], PDO::PARAM_STR);
        $query->bindParam(':ARD_DI', $result_sqlsvr["ARD_DI"], PDO::PARAM_STR);
        $query->bindParam(':ARD_ARCD', $result_sqlsvr["ARD_ARCD"], PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $conn->lastInsertId();

        if ($lastInsertId) {
            echo "Save OK";
        } else {
            echo "Error";
        }

    }

}

$conn_sqlsvr=null;

