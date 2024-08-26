<?php

ini_set('display_errors', 1);
error_reporting(~0);

include ("../config/connect_sqlserver.php");
include ("../config/connect_db.php");

$sql_keymax = " select ADDB_KEY from addrbook order by ADDB_KEY desc  limit 1  ";
$statement = $conn->query($sql_keymax);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $result) {

    $ADDB_KEY_LAST = $result['ADDB_KEY'];

}

$sql_sqlsvr = "select * from addrbook  where ADDB_KEY >= " . $ADDB_KEY_LAST;


//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_sqlsvr);
//fclose($myfile);

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {


    $sql_find = "SELECT * FROM addrbook WHERE ADDB_KEY = '" . $result_sqlsvr["ADDB_KEY"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        echo "Data " . $result_sqlsvr["ADDB_KEY"] . " Already " . "\n\r";

        $sql = "UPDATE addrbook SET ADDB_COMPANY=:ADDB_COMPANY,ADDB_TAX_ID=:ADDB_TAX_ID,ADDB_SEARCH=:ADDB_SEARCH,
        ADDB_BRANCH=:ADDB_BRANCH,ADDB_ADDB_1=:ADDB_ADDB_1,ADDB_ADDB_2=:ADDB_ADDB_2,ADDB_ADDB_3=:ADDB_ADDB_3,ADDB_PROVINCE=:ADDB_PROVINCE,ADDB_PHONE=:ADDB_PHONE
        WHERE ADDB_KEY = :ADDB_KEY ";

        echo " Update Customer : " . $result_sqlsvr["ADDB_KEY"] . " | " . $result_sqlsvr["ADDB_COMPANY"] . " | " . $result_sqlsvr["ADDB_SEARCH"] . "\n\r";

        $query = $conn->prepare($sql);
        $query->bindParam(':ADDB_COMPANY', $result_sqlsvr["ADDB_COMPANY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_TAX_ID', $result_sqlsvr["ADDB_TAX_ID"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_SEARCH', $result_sqlsvr["ADDB_SEARCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_BRANCH', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_1', $result_sqlsvr["ADDB_ADDB_1"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_2', $result_sqlsvr["ADDB_ADDB_2"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_3', $result_sqlsvr["ADDB_ADDB_3"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PROVINCE', $result_sqlsvr["ADDB_PROVINCE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PHONE', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_KEY', $result_sqlsvr["ADDB_KEY"], PDO::PARAM_STR);
        $query->execute();

    } else {

        echo " Insert Customer : " . $result_sqlsvr["ADDB_KEY"] . " | " . $result_sqlsvr["ADDB_COMPANY"] . " | " . $result_sqlsvr["ADDB_SEARCH"] . "\n\r";

        $sql = "INSERT INTO addrbook(ADDB_KEY,ADDB_COMPANY,ADDB_TAX_ID,ADDB_SEARCH,ADDB_BRANCH,ADDB_ADDB_1,ADDB_ADDB_2,ADDB_ADDB_3,ADDB_PROVINCE,ADDB_PHONE )
        VALUES (:ADDB_KEY,:ADDB_COMPANY,:ADDB_TAX_ID,:ADDB_SEARCH,:ADDB_BRANCH,:ADDB_ADDB_1,:ADDB_ADDB_2,:ADDB_ADDB_3,:ADDB_PROVINCE,:ADDB_PHONE )";
        $query = $conn->prepare($sql);
        $query->bindParam(':ADDB_KEY', $result_sqlsvr["ADDB_KEY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_COMPANY', $result_sqlsvr["ADDB_COMPANY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_TAX_ID', $result_sqlsvr["ADDB_TAX_ID"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_SEARCH', $result_sqlsvr["ADDB_SEARCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_BRANCH', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_1', $result_sqlsvr["ADDB_ADDB_1"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_2', $result_sqlsvr["ADDB_ADDB_2"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_3', $result_sqlsvr["ADDB_ADDB_3"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PROVINCE', $result_sqlsvr["ADDB_PROVINCE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PHONE', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $conn->lastInsertId();

        if ($lastInsertId) {
            echo "Save OK";
        } else {
            echo "Error";
        }

/*
        $return_arr[] = array("customer_id" => $result_sqlsvr['AR_CODE'],
            "tax_id" => $result_sqlsvr['ADDB_TAX_ID'],
            "f_name" => $result_sqlsvr['AR_NAME'],
            "phone" => $result_sqlsvr['ADDB_PHONE'],
            "address" => $result_sqlsvr['ADDB_ADDB_1'],
            "tumbol" => $result_sqlsvr['ADDB_ADDB_2'],
            "amphure" => $result_sqlsvr['ADDB_ADDB_3'],
            "province" => $result_sqlsvr['ADDB_PROVINCE'],
            "zipcode" => $result_sqlsvr['ADDB_POST']);
*/
    }
/*
    $customer_data = json_encode($return_arr);
    file_put_contents("customer_data.json", $customer_data);
    echo json_encode($return_arr);
*/

}

$conn_sqlsvr=null;

