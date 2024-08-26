<?php
date_default_timezone_set('Asia/Bangkok');

$WH_CODE = $_POST['WH_CODE'];
$filename = "Data_Stock_Balance-" . $WH_CODE . "-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

include('../config/connect_sqlserver.php');
include('../config/connect_db.php');

$WH_NAME = "";

$sql_get = "SELECT * FROM ims_branch WHERE WH_CODE = '" . $WH_CODE . "'";
$statement = $conn->query($sql_get);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    $WH_NAME = $result['WH_CODE'] . "-" . $result['branch_name'] ;
}

//$my_file = fopen("D-AAA.txt", "w") or die("Unable to open file!");
//fwrite($my_file, $String_Sql . " / " . $WH_CODE . " - " . $WH_NAME);
//fclose($my_file);


$String_Sql =" SELECT SKU_CODE,SKU_NAME,UTQ_NAME,SUM(QTY) AS SUM_QTY from v_stock_movement   
WHERE WH_CODE = '" . $WH_CODE . "'  
GROUP BY SKU_CODE,SKU_NAME ,UTQ_NAME 
ORDER BY SKU_CODE ";

$data = "คลังสินค้า,". $WH_NAME . ",หน่วยนับ,จำนวน\n";

$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();

$loop = 0;

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

    $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
    $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
    $data .= str_replace(",", "^", $row['UTQ_NAME']) . ",";
    $data .= $row['SUM_QTY'] . "\n";

}

// $data = iconv("utf-8", "tis-620", $data);
$data = iconv("utf-8", "windows-874//IGNORE", $data);
echo $data;

exit();