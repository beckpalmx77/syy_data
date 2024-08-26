<?php
include('../config/connect_db.php');
include('../config/lang.php');
include('../util/reorder_record.php');

//กำหนดค่า Access-Control-Allow-Origin ให้ เครื่อง อื่น ๆ สามารถเรียกใช้งานหน้านี้ได้
header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$month = "3";
$year = "2022";


$requestMethod = $_SERVER["REQUEST_METHOD"];

$sql_get = "  SELECT DI_REF,DT_DOCCODE,AR_NAME ,SUM(TRD_G_KEYIN) AS SUM_TOTAL,BRANCH,DI_MONTH_NAME,DI_YEAR FROM ims_product_sale_cockpit 
WHERE DI_MONTH =  " . $month . " AND DI_YEAR = " . $year . "
GROUP BY DI_REF 
ORDER BY AR_NAME,BRANCH ";

//ตรวจสอบหากใช้ Method GET

if ($requestMethod == 'GET') {

    $return_arr = array();

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("DI_REF" => $result['DI_REF'],
            "DT_DOCCODE" => $result['DT_DOCCODE'],
            "AR_NAME" => $result['AR_NAME'],
            "SUM_TOTAL" => $result['SUM_TOTAL'],
            "BRANCH" => $result['BRANCH'],
            "DI_MONTH_NAME" => $result['DI_MONTH_NAME'],
            "DI_YEAR" => $result['DI_YEAR']);
    }

    $dataresult = json_encode($return_arr);
    file_put_contents("ims_product_sale_cockpit.json", $dataresult);
    echo json_encode($return_arr);

}