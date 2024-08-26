<?php
header('Content-Type: application/json');

include("../config/connect_db.php");

$year = $_POST["year"];

$sql_get = "
 SELECT BRN_CODE,BRN_NAME,SKU_CAT,ICCAT_NAME,sum(CAST(TRD_QTY AS DECIMAL(10,2))) as  TRD_QTY
 ,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as TRD_G_KEYIN 
 FROM ims_product_sale_cockpit 
 WHERE PGROUP IN ('P1') AND BRN_CODE <> ''
 AND DI_YEAR = '" . $year . "'
 AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
 GROUP BY BRN_CODE,BRN_NAME,SKU_CAT,ICCAT_NAME
 ORDER BY SKU_CAT 
 ";

$return_arr = array();

$statement = $conn->query($sql_get);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    $return_arr[] = array("BRN_CODE" => $result['BRN_CODE'],
        "BRN_NAME" => $result['BRN_NAME'],
        "TRD_QTY" => $result['TRD_QTY'],
        "TRD_G_KEYIN" => $result['TRD_G_KEYIN']);
}

/*
$myfile = fopen("qry_file_pie2.txt", "w") or die("Unable to open file!");
fwrite($myfile, $sql_get . " | " . $year);
fclose($myfile);
*/

echo json_encode($return_arr);

