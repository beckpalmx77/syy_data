<?php

ini_set('display_errors', 1);
error_reporting(~0);

include ("../config/connect_sqlserver.php");
include ("../config/connect_db.php");

$sql_sqlsvr = "

SELECT 
TRANSTKD.TRD_KEY , 
ADDRBOOK.ADDB_KEY , 
ADDRBOOK.ADDB_BRANCH , 
ADDRBOOK.ADDB_SEARCH ,
ADDRBOOK.ADDB_ADDB_1 , 
ADDRBOOK.ADDB_ADDB_2 , 
ADDRBOOK.ADDB_COMPANY ,
DOCINFO.DI_REF , 
DOCINFO.DI_DATE ,
DAY(DI_DATE) AS DI_DAY ,
MONTH(DI_DATE) AS DI_MONTH ,
YEAR(DI_DATE) AS DI_YEAR ,
TRANSTKH.TRH_DI,
SKUMASTER.SKU_CODE ,
SKUMASTER.SKU_NAME ,
TRANSTKD.TRD_QTY,
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
(ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB) AND 
(ARDETAIL.ARD_AR = ARADDRESS.ARA_AR) AND 
(DOCINFO.DI_KEY = ARDETAIL.ARD_DI) AND 
(DOCINFO.DI_KEY = TRANSTKH.TRH_DI) AND 
(TRANSTKH.TRH_KEY = TRANSTKD.TRD_TRH) AND 
(TRANSTKD.TRD_SKU = SKUMASTER.SKU_KEY) 
 ";



//$query_year = " AND DI_DATE BETWEEN '" . date("Y/m/d", strtotime("yesterday")) . "' AND '" . date("Y/m/d") . "'";
$query_year = " AND DI_DATE BETWEEN '2022/01/01' AND '" . date("Y/m/d") . "'";

$query_order = " ORDER BY TRANSTKD.TRD_KEY ";


//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
////fwrite($myfile, $sql_sqlsvr);
////fclose($myfile);

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr . $query_year . $query_order);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {


    $sql_find = "SELECT * FROM ims_histoty_customer_ar WHERE TRD_KEY = '" . $result_sqlsvr["TRD_KEY"] . "'";
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        $sql = "UPDATE ims_histoty_customer_ar SET ADDB_COMPANY=:ADDB_COMPANY,ADDB_SEARCH=:ADDB_SEARCH,ADDB_BRANCH=:ADDB_BRANCH,
        ADDB_EMAIL=:ADDB_EMAIL,ADDB_ADDB_1=:ADDB_ADDB_1,ADDB_ADDB_2=:ADDB_ADDB_2,ADDB_ADDB_3=:ADDB_ADDB_3,
        ADDB_PROVINCE=:ADDB_PROVINCE,ADDB_PHONE=:ADDB_PHONE,ADDB_REMARK=:ADDB_REMARK,
        DI_REF=:DI_REF,DI_DATE=:DI_DATE,DI_DAY=:DI_DAY,DI_MONTH=:DI_MONTH,DI_YEAR=:DI_YEAR,
        TRH_DI=:TRH_DI,
        SKU_CODE=:SKU_CODE,SKU_NAME=:SKU_NAME,
        TRD_QTY=:TRD_QTY,TRD_U_PRC=:TRD_U_PRC,TRD_B_SELL=:TRD_B_SELL,TRD_B_VAT=:TRD_B_VAT,TRD_B_AMT=:TRD_B_AMT 
        WHERE TRD_KEY = :TRD_KEY ";

        echo " Update Customer : " . $result_sqlsvr["TRD_KEY"] . " | " . $result_sqlsvr["ADDB_COMPANY"] . " | " . $result_sqlsvr["ADDB_SEARCH"] . "\n\r";

        $query = $conn->prepare($sql);
        $query->bindParam(':ADDB_COMPANY', $result_sqlsvr["ADDB_COMPANY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_SEARCH', $result_sqlsvr["ADDB_SEARCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_BRANCH', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_EMAIL', $result_sqlsvr["ADDB_EMAIL"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_1', $result_sqlsvr["ADDB_ADDB_1"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_2', $result_sqlsvr["ADDB_ADDB_2"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_3', $result_sqlsvr["ADDB_ADDB_3"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PROVINCE', $result_sqlsvr["ADDB_PROVINCE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PHONE', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_REMARK', $result_sqlsvr["ADDB_REMARK"], PDO::PARAM_STR);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':DI_DAY', $result_sqlsvr["DI_DAY"], PDO::PARAM_STR);
        $query->bindParam(':DI_MONTH', $result_sqlsvr["DI_MONTH"], PDO::PARAM_STR);
        $query->bindParam(':DI_YEAR', $result_sqlsvr["DI_YEAR"], PDO::PARAM_STR);
        $query->bindParam(':TRH_DI', $result_sqlsvr["TRH_DI"], PDO::PARAM_STR);
        $query->bindParam(':SKU_CODE', $result_sqlsvr["SKU_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SKU_NAME', $result_sqlsvr["SKU_NAME"], PDO::PARAM_STR);
        $query->bindParam(':TRD_QTY', $result_sqlsvr["TRD_QTY"], PDO::PARAM_STR);
        $query->bindParam(':TRD_U_PRC', $result_sqlsvr["TRD_U_PRC"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_SELL', $result_sqlsvr["TRD_B_SELL"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_VAT', $result_sqlsvr["TRD_B_VAT"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_AMT', $result_sqlsvr["TRD_B_AMT"], PDO::PARAM_STR);
        $query->bindParam(':TRD_KEY', $result_sqlsvr["TRD_KEY"], PDO::PARAM_STR);
        $query->execute();
    } else {

        echo " Insert Customer : " . $result_sqlsvr["TRD_KEY"] . " | " . $result_sqlsvr["ADDB_COMPANY"] . " | " . $result_sqlsvr["ADDB_SEARCH"] . "\n\r";

        $sql = "INSERT INTO ims_histoty_customer_ar(TRD_KEY,ADDB_COMPANY,ADDB_SEARCH,ADDB_BRANCH,ADDB_EMAIL,ADDB_ADDB_1,ADDB_ADDB_2,ADDB_ADDB_3,ADDB_PROVINCE,ADDB_PHONE,ADDB_REMARK,DI_REF,DI_DATE,DI_DAY,DI_MONTH,DI_YEAR,TRH_DI
,SKU_CODE,SKU_NAME,TRD_QTY,TRD_U_PRC,TRD_B_SELL,TRD_B_VAT,TRD_B_AMT)
        VALUES (:TRD_KEY,:ADDB_COMPANY,:ADDB_SEARCH,:ADDB_BRANCH,:ADDB_EMAIL,:ADDB_ADDB_1,:ADDB_ADDB_2,:ADDB_ADDB_3,:ADDB_PROVINCE,:ADDB_PHONE,:ADDB_REMARK,:DI_REF,:DI_DATE,:DI_DAY,:DI_MONTH,:DI_YEAR,:TRH_DI
,:SKU_CODE,:SKU_NAME,:TRD_QTY,:TRD_U_PRC,:TRD_B_SELL,:TRD_B_VAT,:TRD_B_AMT)";
        $query = $conn->prepare($sql);
        $query->bindParam(':TRD_KEY', $result_sqlsvr["TRD_KEY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_COMPANY', $result_sqlsvr["ADDB_COMPANY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_SEARCH', $result_sqlsvr["ADDB_SEARCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_BRANCH', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_EMAIL', $result_sqlsvr["ADDB_EMAIL"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_1', $result_sqlsvr["ADDB_ADDB_1"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_2', $result_sqlsvr["ADDB_ADDB_2"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_ADDB_3', $result_sqlsvr["ADDB_ADDB_3"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PROVINCE', $result_sqlsvr["ADDB_PROVINCE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PHONE', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_REMARK', $result_sqlsvr["ADDB_REMARK"], PDO::PARAM_STR);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':DI_DAY', $result_sqlsvr["DI_DAY"], PDO::PARAM_STR);
        $query->bindParam(':DI_MONTH', $result_sqlsvr["DI_MONTH"], PDO::PARAM_STR);
        $query->bindParam(':DI_YEAR', $result_sqlsvr["DI_YEAR"], PDO::PARAM_STR);
        $query->bindParam(':TRH_DI', $result_sqlsvr["TRH_DI"], PDO::PARAM_STR);
        $query->bindParam(':SKU_CODE', $result_sqlsvr["SKU_CODE"], PDO::PARAM_STR);
        $query->bindParam(':SKU_NAME', $result_sqlsvr["SKU_NAME"], PDO::PARAM_STR);
        $query->bindParam(':TRD_QTY', $result_sqlsvr["TRD_QTY"], PDO::PARAM_STR);
        $query->bindParam(':TRD_U_PRC', $result_sqlsvr["TRD_U_PRC"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_SELL', $result_sqlsvr["TRD_B_SELL"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_VAT', $result_sqlsvr["TRD_B_VAT"], PDO::PARAM_STR);
        $query->bindParam(':TRD_B_AMT', $result_sqlsvr["TRD_B_AMT"], PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $conn->lastInsertId();

        if ($lastInsertId) {
            echo "Save OK";
        } else {
            echo "Error";
        }

/*
        $return_arr[] = array("customer_id" => $result_sqlsvr['AR_CODE'],
            "tax_id" => $result_sqlsvr['ADDB_TAX_ID'],
            "f_name" => $result_sqlsvr['AR_NAME'],
            "phone" => $result_sqlsvr['ADDB_PHONE'],
            "address" => $result_sqlsvr['ADDB_ADDB_1'],
            "tumbol" => $result_sqlsvr['ADDB_ADDB_2'],
            "amphure" => $result_sqlsvr['ADDB_ADDB_3'],
            "province" => $result_sqlsvr['ADDB_PROVINCE'],
            "zipcode" => $result_sqlsvr['ADDB_POST']);
*/
    }
/*
    $customer_data = json_encode($return_arr);
    file_put_contents("customer_data.json", $customer_data);
    echo json_encode($return_arr);
*/

}

$conn_sqlsvr=null;

