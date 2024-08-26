<?php
session_start();
error_reporting(0);
include '../config/connect_db.php';

// Number of records fetch
$numberofrecords = 500000;

if(!isset($_POST['searchTerm'])){

    $sql_search = "SELECT DISTINCT(AR_CODE) AS AR_CODE,AR_NAME FROM ims_product_sale_cockpit WHERE AR_CODE IS NOT NULL ORDER BY AR_CODE LIMIT :limit";
    $stmt = $conn->prepare($sql_search);
    $stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
    $stmt->execute();
    $custsList = $stmt->fetchAll();

}else{

    $search = $_POST['searchTerm'];// Search text
    $sql_search = "SELECT DISTINCT(AR_CODE) AS AR_CODE,AR_NAME FROM ims_product_sale_cockpit WHERE AR_CODE IS NOT NULL AND AR_NAME LIKE :AR_NAME ORDER BY AR_CODE LIMIT :limit";

    //$myfile = fopen("qry_file_mysql_server2.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $sql_search);
    //fclose($myfile);

    // Fetch records
    $stmt = $conn->prepare($sql_search);
    $stmt->bindValue(':AR_NAME', '%'.$search.'%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
    $stmt->execute();
    $custsList = $stmt->fetchAll();

}

$response = array();

// Read Data
foreach($custsList as $cust){
    $response[] = array(
        "id" => $cust['AR_NAME'],
        "text" => $cust['AR_NAME'] . " [" . $cust['AR_CODE'] . "]"
    );
}

echo json_encode($response);
exit();
