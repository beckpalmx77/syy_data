<?php
include('../config/connect_sqlserver.php');
include("../config/connect_db.php");
date_default_timezone_set('Asia/Bangkok');

// $customer_name = $_POST["AR_NAME"];
//$car_no = $_POST["car_no"];

$customer_name = "บริษัท ดี.ไดร์เวอร์ กรุงเทพ จำกัด";
$car_no = "";

$sql_data_select = " SELECT 
TRANSTKD.TRD_KEY , 
ADDRBOOK.ADDB_KEY , 
ADDRBOOK.ADDB_BRANCH , 
ADDRBOOK.ADDB_SEARCH ,
ADDRBOOK.ADDB_ADDB_1 , 
ADDRBOOK.ADDB_ADDB_2 , 
ADDRBOOK.ADDB_COMPANY ,
ADDRBOOK.ADDB_PHONE ,
DOCINFO.DI_REF , 
DOCINFO.DI_DATE,
DAY(DI_DATE) AS DI_DAY ,
MONTH(DI_DATE) AS DI_MONTH ,
YEAR(DI_DATE) AS DI_YEAR ,
TRANSTKH.TRH_DI,
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
ADDRBOOK.ADDB_COMPANY like '%" . $customer_name . "%' AND
ADDRBOOK.ADDB_SEARCH like '%" . $car_no . "%' AND
(ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB) AND 
(ARDETAIL.ARD_AR = ARADDRESS.ARA_AR) AND 
(DOCINFO.DI_KEY = ARDETAIL.ARD_DI) AND 
(DOCINFO.DI_KEY = TRANSTKH.TRH_DI) AND 
(TRANSTKH.TRH_KEY = TRANSTKD.TRD_TRH) AND 
(TRANSTKD.TRD_SKU = SKUMASTER.SKU_KEY)

ORDER BY ADDRBOOK.ADDB_COMPANY , TRD_KEY DESC , SKUMASTER.SKU_CODE ";


$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_data_select);
$stmt_sqlsvr->execute();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {

    $sql_find = "SELECT * FROM t_history_customer_service "
        . " WHERE ADDB_BRANCH = '" . $result_sqlsvr["ADDB_BRANCH"]
        . "' AND ADDB_COMPANY = '" . $result_sqlsvr["ADDB_COMPANY"]
        . "' AND ADDB_SEARCH = '" . $result_sqlsvr["ADDB_SEARCH"]
        . "' AND ADDB_ADDB_1 = '" . $result_sqlsvr["ADDB_ADDB_1"]
        . "' AND ADDB_ADDB_2 = '" . $result_sqlsvr["ADDB_ADDB_2"]
        . "' AND DI_REF = '" . $result_sqlsvr["DI_REF"]
        . "' AND DI_DATE = '" . $result_sqlsvr["DI_DATE"]
        . "' AND SKU_CODE = '" . $result_sqlsvr["SKU_CODE"]
        . "' AND SKU_NAME = '" . $result_sqlsvr["SKU_NAME"] . "'"
        . " AND TRD_QTY = " . $result_sqlsvr["TRD_QTY"]
        . " AND TRD_U_PRC = " . $result_sqlsvr["TRD_U_PRC"]
        . " AND TRD_B_SELL = " . $result_sqlsvr["TRD_B_SELL"]
        . " AND TRD_B_VAT = " . $result_sqlsvr["TRD_B_VAT"] ;


    echo $sql_find . "\n\r";

    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        echo "DUP Record " . "\n\r";
    } else {

        $sql = " INSERT INTO t_history_customer_service (ADDB_BRANCH,ADDB_SEARCH,ADDB_ADDB_1,ADDB_ADDB_2,ADDB_COMPANY,ADDB_PHONE,DI_REF,DI_DATE
 ,DI_DAY,DI_MONTH,DI_YEAR,TRH_DI,SKU_CODE,SKU_NAME,TRD_QTY,TRD_Q_FREE,TRD_U_PRC,TRD_B_SELL,TRD_B_VAT,TRD_B_AMT)
        VALUES (:ADDB_BRANCH,:ADDB_SEARCH,:ADDB_ADDB_1,:ADDB_ADDB_2,:ADDB_COMPANY,:ADDB_PHONE,:DI_REF,:DI_DATE
 ,:DI_DAY,:DI_MONTH,:DI_YEAR,:TRH_DI,:SKU_CODE,:SKU_NAME,:TRD_QTY,:TRD_Q_FREE,:TRD_U_PRC,:TRD_B_SELL,:TRD_B_VAT,:TRD_B_AMT) ";

        $TRD_QTY = $result_sqlsvr["TRD_QTY"] ;
        $TRD_Q_FREE = $result_sqlsvr["TRD_Q_FREE"];
        $TRD_U_PRC = $result_sqlsvr["TRD_U_PRC"];
        $TRD_B_SELL = $result_sqlsvr["TRD_B_SELL"];
        $TRD_U_PRC = $result_sqlsvr["TRD_U_PRC"];
        $TRD_B_VAT = $result_sqlsvr["TRD_B_VAT"];
        $TRD_B_AMT = $result_sqlsvr["TRD_B_AMT"];


        $query = $conn->prepare($sql);
        $query->bindParam(':ADDB_BRANCH', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_SEARCH', $result_sqlsvr["ADDB_SEARCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_1', $result_sqlsvr["ADDB_ADDB_1"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_2', $result_sqlsvr["ADDB_ADDB_2"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_COMPANY', $result_sqlsvr["ADDB_COMPANY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PHONE', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':DI_DAY', $result_sqlsvr["DI_DAY"], PDO::PARAM_STR);
        $query->bindParam(':DI_MONTH', $result_sqlsvr["DI_MONTH"], PDO::PARAM_STR);
        $query->bindParam(':DI_YEAR', $result_sqlsvr["DI_YEAR"], PDO::PARAM_STR);
        $query->bindParam(':TRH_DI', $result_sqlsvr["TRH_DI"], PDO::PARAM_STR);

        $query->bindParam(':SKU_CODE', $result_sqlsvr["SKU_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SKU_NAME', $result_sqlsvr["SKU_NAME"], PDO::PARAM_STR);

        $query->bindParam(':TRD_QTY', $TRD_QTY, PDO::PARAM_STR);
        $query->bindParam(':TRD_Q_FREE', $TRD_Q_FREE, PDO::PARAM_STR);
        $query->bindParam(':TRD_U_PRC', $TRD_U_PRC, PDO::PARAM_STR);
        $query->bindParam(':TRD_B_SELL', $TRD_B_SELL, PDO::PARAM_STR);
        $query->bindParam(':TRD_B_VAT', $TRD_B_VAT, PDO::PARAM_STR);
        $query->bindParam(':TRD_B_AMT', $TRD_B_AMT, PDO::PARAM_STR);


        $query->execute();
        $lastInsertId = $conn->lastInsertId();

    }

}


/*

$filename = "Data_Customer_History-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);


$data = "ลำดับที่,เลขที่เอกสาร,วันที่,ชื่อลูกค้า,ทะเบียนรถ,ยี่ห้อรถ/รุ่น,รหัสสินค้า,ชื่อสินค้า,จำนวน,จำนวนเงิน(บาท)\n";

$query = $conn_sqlsvr->prepare($sql_data_select);
$query->execute();

$loop = 0;

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $loop++;
    $TRD_QTY = $row['TRD_Q_FREE'] > 0 ? $row['TRD_QTY'] = $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];
    $data .= $loop . ",";
    $data .= $row['DI_REF'] . ",";
    $data .= $row['DI_DAY'] . "/" . $row['DI_MONTH'] . "/" . $row['DI_YEAR'] . ",";
    $data .= str_replace(",", "^", $row['ADDB_COMPANY']) . "  " . str_replace(",", "^", $row['ADDB_PHONE']) . ",";
    $data .= str_replace(",", "^", $row['ADDB_SEARCH']) . ",";
    $data .= str_replace(",", "^", $row['ADDB_ADDB_1']) . "  " . str_replace(",", "^", $row['ADDB_ADDB_2']) . ",";
    $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
    $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
    $data .= $TRD_QTY . ",";
    $data .= $row['TRD_B_AMT'] . "\n";
}

$data = iconv("utf-8", "tis-620", $data);
echo $data;

        echo $line . " | " . $result_sqlsvr_detail["ADDB_COMPANY"]
            . " | " . $result_sqlsvr_detail["ADDB_BRANCH"]
            . " | " . $result_sqlsvr_detail["ADDB_PHONE"]
            . " | " . $result_sqlsvr_detail["DI_REF"]
            . " | " . $result_sqlsvr_detail["DI_DATE"]
            . " | " . $result_sqlsvr_detail["TRD_QTY"]
            . " | " . $result_sqlsvr_detail["TRD_U_PRC"]
            . " | " . $result_sqlsvr_detail["TRD_B_AMT"]
            . " | " . $result_sqlsvr_detail["SKU_CODE"] . " | " . $result_sqlsvr_detail["SKU_NAME"] . "\n\r" ;


*/


exit();