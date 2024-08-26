<?php
include('../config/connect_sqlserver.php');
include("../config/connect_db.php");
date_default_timezone_set('Asia/Bangkok');

$filename = "Data_Customer_History-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

$ICCAT_NAME = $_POST["ICCAT_NAME"];
$BRAND_NAME = $_POST["BRAND_NAME"];
$BRANCH_NAME = $_POST["BRANCH_NAME"]=="ALL" ? "" : $_POST["BRANCH_NAME"];

$sql_cmd = "";

$data = "ลำดับที่,เลขที่เอกสาร,วันที่,ชื่อลูกค้า,สาขา,รหัสสินค้า,ชื่อสินค้า,ประเภท,ยี่ห้อ,จำนวน,จำนวนเงิน(บาท)\n";

$sql_data_select_main = "SELECT * FROM  ims_product_sale_cockpit "
                      . " WHERE ICCAT_NAME LIKE '%" . $ICCAT_NAME .  "%'"
                      . " AND BRN_NAME LIKE '%" . $BRAND_NAME . "%'"
                      . " AND BRANCH LIKE '%" . $BRANCH_NAME . "%' AND DI_KEY IS NOT NULL " ;

$sql_data_select_order = " ORDER BY BRANCH , DI_YEAR DESC   , DI_MONTH DESC , AR_CODE ";

/*
$myfile = fopen("exp_str.txt", "w") or die("Unable to open file!");
fwrite($myfile, $sql_data_select_main . $sql_data_select_order);
fclose($myfile);
*/


$sql_data_select = $sql_data_select_main . $sql_data_select_order ;

$query = $conn->prepare($sql_data_select);
$query->execute();
$line = 0;

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

        $line++;
        $TRD_QTY = $row['TRD_Q_FREE'] > 0 ? $row['TRD_QTY'] = $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];
        $data .= $line . ",";
        $data .= $row['DI_REF'] . ",";
        $data .= $row['DI_DATE'] . ",";
        //$data .= $row['DI_DAY'] . "/" . $row['DI_MONTH'] . "/" . $row['DI_YEAR'] . ",";
        $data .= str_replace(",", "^", $row['AR_NAME']) . ",";
        $data .= str_replace(",", "^", $row['BRANCH']) . ",";
        $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
        $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
        $data .= str_replace(",", "^", $row['ICCAT_NAME']) . ",";
        $data .= str_replace(",", "^", $row['BRN_NAME']) . ",";
        $data .= $TRD_QTY . ",";
        $data .= $row['TRD_G_KEYIN'] . "\n";

}

// $data = iconv("utf-8", "tis-620", $data);
$data = iconv("utf-8", "windows-874//IGNORE", $data);
echo $data;


exit();