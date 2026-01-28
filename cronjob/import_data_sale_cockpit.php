<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_sqlserver.php");
include("../config/connect_db.php");

include('../cond_file/doc_info_sale_daily_cp.php');
include('../util/month_util.php');

/*
$str_doc1 = array("CS01", "CS09", "CV01", "CV09", "DS01", "DS08", "IV01", "IV08");
$str_doc2 = array("CS08", "CV07", "DS07", "IV07");
$str_doc3 = array("CS02", "CV02", "DS02", "IV02");
$str_doc4 = array("CS03", "CV03", "DS03", "IV03");
$str_doc5 = array("CS04", "CV04", "DS04", "IV04");
$str_doc6 = array("CS05", "CV05", "DS05", "IV05");
$str_doc7 = array("CS14", "CV014", "DS12", "IV12");
$str_doc8 = array("CS15", "CV15", "DS13", "IV13");
*/

$str_doc1 = array("CS01", "CS09", "CV01", "CV09", "DS01", "DS08", "IV01", "IV08", "ISC1", "ISC7", "ISC8", "ICO1", "ICO8", "IS1", "IS7", "ISO1", "ISO7", "IC10");
$str_doc2 = array("CS08", "CV07", "DS07", "IV07", "ISC6", "ICO6", "IS6", "ISO6");
$str_doc3 = array("CS02", "CV02", "DS02", "IV02", "ICO2", "IS2", "ISO2", "ISC2");
$str_doc4 = array("CS03", "CV03", "DS03", "IV03", "ISC3", "ICO3", "IS3", "ISO3");
$str_doc5 = array("CS04", "CV04", "DS04", "IV04", "ISC4", "ICO4", "IS4", "ISO4");
$str_doc6 = array("CS05", "CV05", "DS05", "IV05", "ISC5", "ICO5", "IS5", "ISO5");
$str_doc7 = array("CS14", "CV014", "DS12", "IV12", "ICO9", "ISC9", "IS8", "ISO8");
$str_doc8 = array("CS15", "CV15", "DS13", "IV13", "IC11", "IS10", "IS9", "ISO9");


$str_group1 = array("201-DT01", "027-TOYO01", "020-MX01", "201-BS01", "201-LLIT01", "201-ML01",
    "025-T%01", "101-LLIT01", "201-DL01", "201-LEAO01", "023-NT01", "201-SIM01",
    "024-O01", "201-FS01", "101-BS03", "102-CBS01", "103-LBS01", "102-CMT01",
    "103-LMT01", "101-VR01", "102-CDS01", "103-LKS01", "101-ML01", "401-WIL01",
    "201-PX01", "201-DS01", "101-BS01", "007-T%01", "101-DS01", "201-BF01",
    "201-FT01", "201-VR01", "017-HK01", "028-YK01", "026-T01", "006-RS01",
    "009-CO01", "011-LO01", "101-FS02", "005-O01", "201-GT01", "014-CT01",
    "101-BS02", "202-NK01", "002-BKT01", "103-LDS01", "101-DS02", "101-AT01",
    "101-FS03", "101-SIM01", "102-CKS01", "102-CKL01", "201-TBB01", "016-GY01");

$str_group2 = array("602-1", "603", "602-1", "034-J01", "201-AT01", "601-1", "603-1",
    "602-2", "601-2", "029-001", "030-002", "401-WIL02", "602-3-ALL01",
    "033-005", "031-003", "037-J02", "401-KV01", "501-03", "601+++++++++","999-28", "601-3"
);

$str_group3 = array("999-03", "999-12", "999-08", "999-04", "999-14", "036-01",
    "999-10", "035-00", "999-17", "999-20", "999-15", "999-18",
    "999-21");

$str_group4 = array(
    "999-07", "999-09", "999-01", "999-11", "999-13", "A502-ALL03",
    "999-25", "999-05"
);

$str_group5 = array("99998", "9-9402-ALL02", "9999-2", "501-02", "99999");

//$query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('30','CS4','CS5','DS4','IS3','IS4','ISC3','ISC4','CS.8','CS.9','IC.3','IC.4','IS.3','IS.4','S.5','S.6','CS.6','CS.7','IC.1','IC.2','IS.1','IS.2','S.1','S.2','CS.2','CS.3','IC.5','IC.6','IS.5','IS.6','S.3','S.4')) ";

// 1. รวม Array ทั้งหมดเข้าด้วยกัน
$all_docs = array_merge($str_doc1, $str_doc2, $str_doc3, $str_doc4, $str_doc5, $str_doc6, $str_doc7, $str_doc8);

// 2. สร้าง String สำหรับใส่ในเงื่อนไข IN (...)
// โดยใส่เครื่องหมาย ' (single quote) คร่อมแต่ละค่า และคั่นด้วย , (comma)
$in_clause = "'" . implode("', '", $all_docs) . "'";

// 3. สร้างเงื่อนไข SQL ทั้งหมด
$query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in (" . $in_clause . ")) ";

//$query_year = " AND DI_DATE BETWEEN '2017/01/01' AND '2023/07/31'";

$query_year = " AND DI_DATE BETWEEN '2025/01/01' AND '" . date("Y/m/d") . "'";

//$query_year = " AND DI_DATE BETWEEN '" . date("Y/m/d", strtotime("yesterday")) . "' AND '" . date("Y/m/d") . "'";

echo "Today is " . date("Y/m/d") . "\n\r" ;
echo "Yesterday is " . date("Y/m/d", strtotime("yesterday")) . "\n\r" ;
echo "Host - " . $host . "\n\r";

$sql_sqlsvr = $select_query_daily . $select_query_daily_cond . $query_daily_cond_ext . $query_year . $select_query_daily_order;

echo $sql_sqlsvr;

/*
$myfile = fopen("qry_file_mssql_server.txt", "w") or die("Unable to open file!");
fwrite($myfile, $sql_sqlsvr);
fclose($myfile);
*/

$insert_data = "";
$update_data = "";

$res = "";

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {

    $ICCAT_CODE = "";

    $DT_DOCCODE = $result_sqlsvr["DT_DOCCODE"];
    $ICCAT_CODE = $result_sqlsvr["ICCAT_CODE"];

    $TRD_QTY = $result_sqlsvr['TRD_Q_FREE'] > 0 ? $result_sqlsvr['TRD_QTY'] + $result_sqlsvr['TRD_Q_FREE'] : $result_sqlsvr['TRD_QTY'];

    $branch = "";

    if (in_array($DT_DOCCODE, $str_doc1)) {
        $branch = "SYY01";
    }

    if (in_array($DT_DOCCODE, $str_doc2)) {
        $branch = "SYY02";
    }

    if (in_array($DT_DOCCODE, $str_doc3)) {
        $branch = "SYY03";
    }

    if (in_array($DT_DOCCODE, $str_doc4)) {
        $branch = "SYY04";
    }

    if (in_array($DT_DOCCODE, $str_doc5)) {
        $branch = "SYY05";
    }

    if (in_array($DT_DOCCODE, $str_doc6)) {
        $branch = "SYY06";
    }

    if (in_array($DT_DOCCODE, $str_doc7)) {
        $branch = "SYY07";
    }

    if (in_array($DT_DOCCODE, $str_doc8)) {
        $branch = "SYY08";
    }

    echo "[ " . $DT_DOCCODE . " | " . $branch . " ]" . "\n\r";

    $res = $res . $result_sqlsvr["DI_REF"] . "  *** " . $result_sqlsvr["DT_DOCCODE"] . " *** " . "\n\r";

    //$myfile = fopen("sql_get_DATA.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, "[" . $res) ;
    //fclose($myfile);


    $p_group = "";

    if (in_array($ICCAT_CODE, $str_group1)) {
        $p_group = "P1";
    }

    if (in_array($ICCAT_CODE, $str_group2)) {
        $p_group = "P2";
    }

    if (in_array($ICCAT_CODE, $str_group3)) {
        $p_group = "P3";
    }

    if (in_array($ICCAT_CODE, $str_group4)) {
        $p_group = "P4";
    }

    if (in_array($ICCAT_CODE, $str_group5)) {
        $p_group = "P5";
    }

    $sql_find = "SELECT * FROM ims_product_sale_cockpit "
        . " WHERE DI_KEY = '" . $result_sqlsvr["DI_KEY"]
        . "' AND DI_REF = '" . $result_sqlsvr["DI_REF"]
        . "' AND DI_DATE = '" . $result_sqlsvr["DI_DATE"]
        . "' AND DT_DOCCODE = '" . $result_sqlsvr["DT_DOCCODE"]
        . "' AND TRD_SEQ = '" . $result_sqlsvr["TRD_SEQ"] . "'";

    //echo $sql_find . "\n\r";

    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {

        $sql_update = " UPDATE ims_product_sale_cockpit  SET AR_CODE=:AR_CODE,AR_NAME=:AR_NAME,SLMN_CODE=:SLMN_CODE,SLMN_NAME=:SLMN_NAME
,SKU_CODE=:SKU_CODE,SKU_NAME=:SKU_NAME,SKU_CAT=:SKU_CAT,ICCAT_CODE=:ICCAT_CODE,ICCAT_NAME=:ICCAT_NAME,TRD_QTY=:TRD_QTY,TRD_Q_FREE=:TRD_Q_FREE,TRD_U_PRC=:TRD_U_PRC
,TRD_DSC_KEYINV=:TRD_DSC_KEYINV,TRD_B_SELL=:TRD_B_SELL
,TRD_B_VAT=:TRD_B_VAT,TRD_G_KEYIN=:TRD_G_KEYIN,WL_CODE=:WL_CODE,BRANCH=:BRANCH,BRN_CODE=:BRN_CODE
,BRN_NAME=:BRN_NAME,DI_TIME_CHK=:DI_TIME_CHK,PGROUP=:PGROUP,DI_ACTIVE=:DI_ACTIVE    
        WHERE DI_KEY = :DI_KEY         
        AND DI_REF  = :DI_REF
        AND DI_DATE = :DI_DATE
        AND DT_DOCCODE = :DT_DOCCODE
        AND TRD_SEQ = :TRD_SEQ ";

        $query = $conn->prepare($sql_update);
        $query->bindParam(':AR_CODE', $result_sqlsvr["AR_CODE"], PDO::PARAM_STR);
        $query->bindParam(':AR_NAME', $result_sqlsvr["AR_NAME"], PDO::PARAM_STR);
        $query->bindParam(':SLMN_CODE', $result_sqlsvr["SLMN_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SLMN_NAME', $result_sqlsvr["SLMN_NAME"], PDO::PARAM_STR);
        $query->bindParam(':SKU_CODE', $result_sqlsvr["SKU_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SKU_NAME', $result_sqlsvr["SKU_NAME"], PDO::PARAM_STR);
        $query->bindParam(':SKU_CAT', $result_sqlsvr["ICCAT_CODE"], PDO::PARAM_STR);
        $query->bindParam(':ICCAT_CODE', $result_sqlsvr["ICCAT_CODE"], PDO::PARAM_STR);
        $query->bindParam(':ICCAT_NAME', $result_sqlsvr["ICCAT_NAME"], PDO::PARAM_STR);
        //$query->bindParam(':TRD_QTY', $result_sqlsvr["TRD_QTY"], PDO::PARAM_STR);
        $query->bindParam(':TRD_QTY', $TRD_QTY, PDO::PARAM_STR);
        $query->bindParam(':TRD_Q_FREE', $result_sqlsvr["TRD_Q_FREE"], PDO::PARAM_STR);
        $query->bindParam(':TRD_U_PRC', $result_sqlsvr["TRD_U_PRC"], PDO::PARAM_STR);
        $query->bindParam(':TRD_DSC_KEYINV', $result_sqlsvr["TRD_DSC_KEYINV"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_SELL', $result_sqlsvr["TRD_B_SELL"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_VAT', $result_sqlsvr["TRD_B_VAT"], PDO::PARAM_STR);
        $query->bindParam(':TRD_G_KEYIN', $result_sqlsvr["TRD_G_KEYIN"], PDO::PARAM_STR);
        $query->bindParam(':WL_CODE', $result_sqlsvr["WL_CODE"], PDO::PARAM_STR);

        $query->bindParam(':BRANCH', $branch, PDO::PARAM_STR);
        $query->bindParam(':BRN_CODE', $result_sqlsvr["BRN_CODE"], PDO::PARAM_STR);
        $query->bindParam(':BRN_NAME', $result_sqlsvr["BRN_NAME"], PDO::PARAM_STR);
        $query->bindParam(':DI_TIME_CHK', $result_sqlsvr["DI_TIME_CHK"], PDO::PARAM_STR);
        $query->bindParam(':PGROUP', $p_group, PDO::PARAM_STR);
        $query->bindParam(':DI_ACTIVE', $result_sqlsvr["DI_ACTIVE"], PDO::PARAM_STR);

        $query->bindParam(':DI_KEY', $result_sqlsvr["DI_KEY"], PDO::PARAM_STR);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':DT_DOCCODE', $result_sqlsvr["DT_DOCCODE"], PDO::PARAM_STR);
        $query->bindParam(':TRD_SEQ', $result_sqlsvr["TRD_SEQ"], PDO::PARAM_STR);

        $query->execute();

        $update_data .= $result_sqlsvr["DI_DATE"] . ":" . $result_sqlsvr["DI_REF"] . " |- " . $result_sqlsvr["ICCAT_CODE"] . "\n\r";

        echo " UPDATE DATA " . $update_data;

        //$myfile = fopen("update_chk.txt", "w") or die("Unable to open file!");
        //fwrite($myfile, $update_data);
        //fclose($myfile);

    } else {

        $sql = " INSERT INTO ims_product_sale_cockpit (DI_KEY,DI_REF,DI_DATE,DI_MONTH,DI_MONTH_NAME,DI_YEAR
        ,AR_CODE,AR_NAME,SLMN_CODE,SLMN_NAME,SKU_CODE,SKU_NAME,SKU_CAT,ICCAT_CODE,ICCAT_NAME,TRD_QTY,TRD_Q_FREE,TRD_U_PRC
        ,TRD_DSC_KEYINV,TRD_B_SELL,TRD_B_VAT,TRD_G_KEYIN,WL_CODE,BRANCH,DT_DOCCODE,TRD_SEQ,BRN_CODE,BRN_NAME,DI_TIME_CHK,PGROUP,DI_ACTIVE)
        VALUES (:DI_KEY,:DI_REF,:DI_DATE,:DI_MONTH,:DI_MONTH_NAME,:DI_YEAR,:AR_CODE,:AR_NAME,:SLMN_CODE,:SLMN_NAME,:SKU_CODE,:SKU_NAME,:SKU_CAT
        ,:ICCAT_CODE,:ICCAT_NAME,:TRD_QTY,:TRD_Q_FREE,:TRD_U_PRC,:TRD_DSC_KEYINV,:TRD_B_SELL,:TRD_B_VAT,:TRD_G_KEYIN
        ,:WL_CODE,:BRANCH,:DT_DOCCODE,:TRD_SEQ,:BRN_CODE,:BRN_NAME,:DI_TIME_CHK,:PGROUP,:DI_ACTIVE) ";
        $query = $conn->prepare($sql);
        $query->bindParam(':DI_KEY', $result_sqlsvr["DI_KEY"], PDO::PARAM_STR);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':DI_MONTH', $result_sqlsvr["DI_MONTH"], PDO::PARAM_STR);
        $query->bindParam(':DI_MONTH_NAME', $month_arr[$result_sqlsvr["DI_MONTH"]], PDO::PARAM_STR);
        $query->bindParam(':DI_YEAR', $result_sqlsvr["DI_YEAR"], PDO::PARAM_STR);
        $query->bindParam(':AR_CODE', $result_sqlsvr["AR_CODE"], PDO::PARAM_STR);
        $query->bindParam(':AR_NAME', $result_sqlsvr["AR_NAME"], PDO::PARAM_STR);
        $query->bindParam(':SLMN_CODE', $result_sqlsvr["SLMN_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SLMN_NAME', $result_sqlsvr["SLMN_NAME"], PDO::PARAM_STR);
        $query->bindParam(':SKU_CODE', $result_sqlsvr["SKU_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SKU_NAME', $result_sqlsvr["SKU_NAME"], PDO::PARAM_STR);
        $query->bindParam(':SKU_CAT', $result_sqlsvr["ICCAT_CODE"], PDO::PARAM_STR);
        $query->bindParam(':ICCAT_CODE', $result_sqlsvr["ICCAT_CODE"], PDO::PARAM_STR);
        $query->bindParam(':ICCAT_NAME', $result_sqlsvr["ICCAT_NAME"], PDO::PARAM_STR);
        //$query->bindParam(':TRD_QTY', $result_sqlsvr["TRD_QTY"], PDO::PARAM_STR);
        $query->bindParam(':TRD_QTY', $TRD_QTY, PDO::PARAM_STR);
        $query->bindParam(':TRD_Q_FREE', $result_sqlsvr["TRD_Q_FREE"], PDO::PARAM_STR);
        $query->bindParam(':TRD_U_PRC', $result_sqlsvr["TRD_U_PRC"], PDO::PARAM_STR);
        $query->bindParam(':TRD_DSC_KEYINV', $result_sqlsvr["TRD_DSC_KEYINV"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_SELL', $result_sqlsvr["TRD_B_SELL"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_VAT', $result_sqlsvr["TRD_B_VAT"], PDO::PARAM_STR);
        $query->bindParam(':TRD_G_KEYIN', $result_sqlsvr["TRD_G_KEYIN"], PDO::PARAM_STR);
        $query->bindParam(':WL_CODE', $result_sqlsvr["WL_CODE"], PDO::PARAM_STR);

        $query->bindParam(':BRANCH', $branch, PDO::PARAM_STR);

        $query->bindParam(':DT_DOCCODE', $DT_DOCCODE, PDO::PARAM_STR);

        $query->bindParam(':TRD_SEQ', $result_sqlsvr["TRD_SEQ"], PDO::PARAM_STR);

        $query->bindParam(':BRN_CODE', $result_sqlsvr["BRN_CODE"], PDO::PARAM_STR);

        $query->bindParam(':BRN_NAME', $result_sqlsvr["BRN_NAME"], PDO::PARAM_STR);

        $query->bindParam(':DI_TIME_CHK', $result_sqlsvr["DI_TIME_CHK"], PDO::PARAM_STR);

        $query->bindParam(':PGROUP', $p_group, PDO::PARAM_STR);

        $query->bindParam(':DI_ACTIVE', $result_sqlsvr["DI_ACTIVE"], PDO::PARAM_STR);



        $query->execute();

        $lastInsertId = $conn->lastInsertId();

        if ($lastInsertId) {
            $insert_data .= $result_sqlsvr["DI_DATE"] . ":" . $result_sqlsvr["DI_REF"] . " | ";
            echo " Save OK " . $insert_data . "\n\r";
        } else {
            echo " Error ";
        }

    }

}

$conn_sqlsvr = null;

