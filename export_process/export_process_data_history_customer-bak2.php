<?php
include('../config/connect_sqlserver.php');
include("../config/connect_db.php");
date_default_timezone_set('Asia/Bangkok');

$filename = "Data_Customer_History-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

$customer_name = $_POST["AR_NAME"];
$car_no = $_POST["car_no"];

$addb_phone = "";

$doc_date_start = substr($_POST['doc_date_start'], 6, 4) . "/" . substr($_POST['doc_date_start'], 3, 2) . "/" . substr($_POST['doc_date_start'], 0, 2);
$doc_date_to = substr($_POST['doc_date_to'], 6, 4) . "/" . substr($_POST['doc_date_to'], 3, 2) . "/" . substr($_POST['doc_date_to'], 0, 2);

$sql_where_ext = " AND DI_DATE BETWEEN '" . $doc_date_start . "' AND '" . $doc_date_to . "' " ;

$sql_cmd = "";

$data = "ลำดับที่,เลขที่เอกสาร,วันที่,ชื่อลูกค้า,หมายเลขโทรศัพท์,ทะเบียนรถ,ยี่ห้อรถ/รุ่น,เลขไมล์,รหัสสินค้า,ชื่อสินค้า,จำนวน,จำนวนเงิน(บาท)\n";


$sql_data_selectDetail =  "   SELECT 
TRANSTKD.TRD_KEY , 
ADDRBOOK.ADDB_KEY , 
ADDRBOOK.ADDB_BRANCH , 
ADDRBOOK.ADDB_SEARCH ,
ADDRBOOK.ADDB_ADDB_1 , 
ADDRBOOK.ADDB_ADDB_2 , 
ADDRBOOK.ADDB_ADDB_3 ,
ADDRBOOK.ADDB_COMPANY ,
ADDRBOOK.ADDB_PHONE ,
DOCINFO.DI_REF , 
DOCINFO.DI_DATE,
DAY(DI_DATE) AS DI_DAY ,
MONTH(DI_DATE) AS DI_MONTH ,
YEAR(DI_DATE) AS DI_YEAR ,
TRANSTKH.TRH_DI,
TRANSTKH.TRH_SHIP_ADDB,
SKUMASTER.SKU_CODE ,
SKUMASTER.SKU_NAME ,
TRANSTKD.TRD_QTY,
TRANSTKD.TRD_Q_FREE,
TRANSTKD.TRD_U_PRC,
TRANSTKD.TRD_B_SELL,
TRANSTKD.TRD_B_VAT,
TRANSTKD.TRD_B_AMT

FROM 
ADDRBOOK,
ARADDRESS,
ARDETAIL,
DOCINFO ,
TRANSTKH ,
TRANSTKD ,
SKUMASTER
 
WHERE
ADDRBOOK.ADDB_COMPANY like '%".$customer_name. "%' AND
ADDRBOOK.ADDB_BRANCH like '%".$car_no. "%' AND
(ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB) AND 
TRANSTKH.TRH_SHIP_ADDB = ADDRBOOK.ADDB_KEY AND 
(ARDETAIL.ARD_AR = ARADDRESS.ARA_AR) AND 
(DOCINFO.DI_KEY = ARDETAIL.ARD_DI) AND 
(DOCINFO.DI_KEY = TRANSTKH.TRH_DI) AND 
(TRANSTKH.TRH_KEY = TRANSTKD.TRD_TRH) AND 
(TRANSTKD.TRD_SKU = SKUMASTER.SKU_KEY)   ";

$order_by = " ORDER BY ADDRBOOK.ADDB_COMPANY , ADDRBOOK.ADDB_BRANCH , TRANSTKD.TRD_KEY , SKUMASTER.SKU_CODE  ";

    $sql_string = $sql_data_selectDetail . $sql_where_ext . $order_by ;
/*
    $myfile = fopen("qry_file_mysql_server2.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $sql_string);
    fclose($myfile);
*/

    $statement_sqlsvr = $conn_sqlsvr->prepare($sql_string);
    $statement_sqlsvr->execute();
    $line = 0 ;
    while ($result_sqlsvr_detail = $statement_sqlsvr->fetch(PDO::FETCH_ASSOC)) {

        $line++;
        $sql_cust_string = "
        SELECT ADDRBOOK.ADDB_PHONE,ARADDRESS.ARA_ADDB
        FROM ARADDRESS
        LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
        WHERE ADDRBOOK.ADDB_COMPANY LIKE '%" . $result_sqlsvr_detail['ADDB_COMPANY'] . "%' AND ARADDRESS.ARA_DEFAULT = 'Y' ";
        $statement_cust_sqlsvr = $conn_sqlsvr->prepare($sql_cust_string);
        $statement_cust_sqlsvr->execute();
        while ($result_sqlsvr_cust = $statement_cust_sqlsvr->fetch(PDO::FETCH_ASSOC)) {
                $addb_phone = "^" . $result_sqlsvr_cust['ADDB_PHONE'];
        }

        $TRD_QTY = $result_sqlsvr_detail['TRD_Q_FREE'] > 0 ? $result_sqlsvr_detail['TRD_QTY'] = $result_sqlsvr_detail['TRD_QTY'] + $result_sqlsvr_detail['TRD_Q_FREE'] : $result_sqlsvr_detail['TRD_QTY'];

        $data .= $line . ",";
        $data .= $result_sqlsvr_detail['DI_REF'] . ",";
        $data .= $result_sqlsvr_detail['DI_DAY'] . "/" . $result_sqlsvr_detail['DI_MONTH'] . "/" . $result_sqlsvr_detail['DI_YEAR'] . ",";
        $data .= str_replace(",", "^", $result_sqlsvr_detail['ADDB_COMPANY']) . ",";
        $data .= str_replace(",", "^", $addb_phone===null?"-":$addb_phone) . ",";
        $data .= str_replace(",", "^", $result_sqlsvr_detail['ADDB_BRANCH']===null?"-":$result_sqlsvr_detail['ADDB_BRANCH']) . ",";
        $data .= str_replace(",", "^", $result_sqlsvr_detail['ADDB_ADDB_1']) . "  " . str_replace(",", "^", $result_sqlsvr_detail['ADDB_ADDB_2']) . ",";
        $data .= str_replace(",", "^", $result_sqlsvr_detail['ADDB_ADDB_3']) . ",";
        $data .= str_replace(",", "^", $result_sqlsvr_detail['SKU_CODE']) . ",";
        $data .= str_replace(",", "^", $result_sqlsvr_detail['SKU_NAME']) . ",";
        $data .= $TRD_QTY . ",";
        $data .= $result_sqlsvr_detail['TRD_B_AMT'] . "\n";

    }


// $data = iconv("utf-8", "tis-620", $data);
$data = iconv("utf-8", "windows-874//IGNORE", $data);
echo $data;


exit();