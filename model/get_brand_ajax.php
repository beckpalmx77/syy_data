<?php
session_start();
error_reporting(0);
include '../config/connect_db.php';

// Number of records fetch
$numberofrecords = 500000;

if(!isset($_POST['searchTerm'])){

    $sql_search = "SELECT DISTINCT(BRN_CODE) AS BRN_CODE,BRN_NAME FROM ims_product_sale_cockpit WHERE BRN_CODE IS NOT NULL ORDER BY BRN_CODE LIMIT :limit";
    $stmt = $conn->prepare($sql_search);
    $stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
    $stmt->execute();
    $servicesList = $stmt->fetchAll();

}else{

    $search = $_POST['searchTerm'];// Search text
    $sql_search = "SELECT DISTINCT(BRN_CODE) AS BRN_CODE,BRN_NAME FROM ims_product_sale_cockpit WHERE BRN_CODE IS NOT NULL AND BRN_NAME LIKE :BRN_NAME ORDER BY BRN_CODE LIMIT :limit";

    //$myfile = fopen("qry_file_mysql_server2.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $sql_search);
    //fclose($myfile);

    // Fetch records
    $stmt = $conn->prepare($sql_search);
    $stmt->bindValue(':BRN_NAME', '%'.$search.'%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
    $stmt->execute();
    $servicesList = $stmt->fetchAll();

}

$response = array();

// Read Data
foreach($servicesList as $service){
    $response[] = array(
        "id" => $service['BRN_NAME'],
        "text" => $service['BRN_NAME'] . " [" . $service['BRN_CODE'] . "]"
    );
}

echo json_encode($response);
exit();
