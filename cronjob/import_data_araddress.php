<?php

ini_set('display_errors', 1);
error_reporting(~0);

include ("../config/connect_sqlserver.php");
include ("../config/connect_db.php");


$sql_keymax = " select ARA_KEY from araddress order by ARA_KEY desc  limit 1  ";
$statement = $conn->query($sql_keymax);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $result) {

    $ARA_KEY_LAST = $result['ARA_KEY'];

}

$sql_sqlsvr = "select * from araddress  where ARA_KEY >= " . $ARA_KEY_LAST;

//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_sqlsvr);
//fclose($myfile);

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {


    $sql_find = "SELECT * FROM araddress WHERE ARA_KEY = '" . $result_sqlsvr["ARA_KEY"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        $sql = "UPDATE araddress SET ARA_AR=:ARA_AR,ARA_ADDB=:ARA_ADDB,ARA_DEFAULT=:ARA_DEFAULT,ARA_LASTUPD=:ARA_LASTUPD
        WHERE ARA_KEY = :ARA_KEY ";

        echo " Update araddress : " . $result_sqlsvr["ARA_KEY"] . " | " . $result_sqlsvr["ARA_AR"] . " | " . $result_sqlsvr["ARA_ADDB"] . "\n\r";

        $query = $conn->prepare($sql);
        $query->bindParam(':ARA_AR', $result_sqlsvr["ARA_AR"], PDO::PARAM_STR);
        $query->bindParam(':ARA_ADDB', $result_sqlsvr["ARA_ADDB"], PDO::PARAM_STR);
        $query->bindParam(':ARA_DEFAULT', $result_sqlsvr["ARA_DEFAULT"], PDO::PARAM_STR);
        $query->bindParam(':ARA_LASTUPD', $result_sqlsvr["ARA_LASTUPD"], PDO::PARAM_STR);
        $query->bindParam(':ARA_KEY', $result_sqlsvr["ARA_KEY"], PDO::PARAM_STR);
        $query->execute();
    } else {

        echo " Insert araddress : " . $result_sqlsvr["ARA_KEY"] . " | " . $result_sqlsvr["ARA_AR"] . " | " . $result_sqlsvr["ARA_ADDB"] . "\n\r";

        $sql = "INSERT INTO araddress(ARA_KEY,ARA_AR,ARA_ADDB,ARA_DEFAULT,ARA_LASTUPD)
        VALUES (:ARA_KEY,:ARA_AR,:ARA_ADDB,:ARA_DEFAULT,:ARA_LASTUPD)";
        $query = $conn->prepare($sql);
        $query->bindParam(':ARA_KEY', $result_sqlsvr["ARA_KEY"], PDO::PARAM_STR);
        $query->bindParam(':ARA_AR', $result_sqlsvr["ARA_AR"], PDO::PARAM_STR);
        $query->bindParam(':ARA_ADDB', $result_sqlsvr["ARA_ADDB"], PDO::PARAM_STR);
        $query->bindParam(':ARA_DEFAULT', $result_sqlsvr["ARA_DEFAULT"], PDO::PARAM_STR);
        $query->bindParam(':ARA_LASTUPD', $result_sqlsvr["ARA_LASTUPD"], PDO::PARAM_STR);
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

