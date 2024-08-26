<?php
session_start();
error_reporting(0);
include '../config/connect_db.php';

// Number of records fetch
$numberofrecords = 500000;

if(!isset($_POST['searchTerm'])){

    $sql_search = "SELECT BRANCH,BRANCH_NAME FROM ims_branch WHERE BRANCH IS NOT NULL ORDER BY BRANCH LIMIT :limit";
    $stmt = $conn->prepare($sql_search);
    $stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
    $stmt->execute();
    $branchsList = $stmt->fetchAll();

}else{

    $search = $_POST['searchTerm'];// Search text
    $sql_search = "SELECT BRANCH,BRANCH_NAME FROM ims_branch WHERE BRANCH IS NOT NULL AND BRANCH_NAME LIKE :BRANCH_NAME ORDER BY BRANCH LIMIT :limit";

    //$myfile = fopen("qry_file_mysql_server2.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $sql_search);
    //fclose($myfile);

    // Fetch records
    $stmt = $conn->prepare($sql_search);
    $stmt->bindValue(':BRANCH_NAME', '%'.$search.'%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
    $stmt->execute();
    $branchsList = $stmt->fetchAll();

}

$response = array();

// Read Data
foreach($branchsList as $branch){
    $response[] = array(
        "id" => $branch['BRANCH'],
        "text" => $branch['BRANCH'] . " [" . $branch['BRANCH_NAME'] . "]"
    );
}

echo json_encode($response);
exit();
