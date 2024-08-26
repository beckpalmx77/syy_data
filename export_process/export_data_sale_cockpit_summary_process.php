<?php
date_default_timezone_set('Asia/Bangkok');

$branch = $_POST["branch"];

$filename = $branch . "-" . "Data_Sale_Summary-Cockpit-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

include('../config/connect_db.php');

$DI_MONTH = $_POST['month'];
$DI_YEAR = $_POST['year'];
$BRANCH = $_POST['branch'];

$sql_summary = "SELECT DI_DATE,BRANCH,SUM(TRD_B_SELL) AS SUM_TRD_B_SELL,SUM(TRD_B_VAT) AS SUM_TRD_B_VAT,SUM(TRD_G_KEYIN) AS SUM_TRD_G_KEYIN 
FROM  ims_product_sale_cockpit 
WHERE DI_MONTH = '" . $DI_MONTH . "' AND DI_YEAR = '" . $DI_YEAR . "' AND BRANCH = '" . $BRANCH . "' 
GROUP BY DI_DATE,BRANCH 
ORDER BY BRANCH,DI_DATE ";

/*
$my_file = fopen("D-CP.txt", "w") or die("Unable to open file!");
fwrite($my_file, $sql_summary);
fclose($my_file);
*/

$data = "วันที่,สาขา,จำนวนลูกค้า,มูลค่ารวม,ภาษี 7%,มูลค่ารวมภาษี\n";
$query = $conn->prepare($sql_summary);
$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

    $sql_count = "SELECT AR_CODE FROM ims_product_sale_cockpit 
                              WHERE  DI_DATE = '" . $row['DI_DATE'] . "' and BRANCH = '" . $row['BRANCH'] . "' GROUP BY AR_CODE";
    $statement_count = $conn->query($sql_count);
    $results_count = $statement_count->fetchAll(PDO::FETCH_ASSOC);
    $customer_count = 0;
    foreach ($results_count as $row_count) {
        $customer_count++;
    }

    $data .= " " . $row['DI_DATE'] . ",";
    $data .= " " . $row['BRANCH'] . ",";
    $data .= " " . $customer_count . ",";
    $data .= " " . $row['SUM_TRD_B_SELL'] . ",";
    $data .= " " . $row['SUM_TRD_B_VAT'] . ",";
    $data .= " " . $row['SUM_TRD_G_KEYIN'] . "\n";

}



$data = iconv("utf-8", "tis-620//IGNORE", $data);
echo $data;

exit();