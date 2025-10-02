<?php
date_default_timezone_set('Asia/Bangkok');

$WH_CODE = $_POST['WH_CODE'];
$filename = "Data_Stock_Balance-" . $WH_CODE . "-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

include('../config/connect_sqlserver.php');
include('../config/connect_db.php');

$WHERE = " WHERE UTQ_NAME = 'เส้น' AND SKU_CODE LIKE '[^0-9]%'  ";

$String_Sql = "SELECT 
    SM.SKU_CODE,
    SM.SKU_NAME,    
    WH.WH_CODE,
    WL.WL_CODE,
    UQ.UTQ_NAME AS UNIT_NAME,
    SUM(SKM.SKM_QTY) AS SUM_QTY
FROM 
    dbo.SKUMASTER SM
    INNER JOIN dbo.ICCAT ON SM.SKU_ICCAT = ICCAT.ICCAT_KEY
    INNER JOIN dbo.ICDEPT ON SM.SKU_ICDEPT = ICDEPT.ICDEPT_KEY
    INNER JOIN dbo.BRAND ON SM.SKU_BRN = BRAND.BRN_KEY
    INNER JOIN dbo.UOFQTY UQ ON SM.SKU_S_UTQ = UQ.UTQ_KEY
    INNER JOIN dbo.SKUMOVE SKM ON SM.SKU_KEY = SKM.SKM_SKU
    INNER JOIN dbo.WARELOCATION WL ON SKM.SKM_WL = WL.WL_KEY
    INNER JOIN dbo.WAREHOUSE WH ON WL.WL_WH = WH.WH_KEY
    INNER JOIN dbo.DOCINFO ON SKM.SKM_DI = DOCINFO.DI_KEY
    INNER JOIN dbo.DOCTYPE ON DOCINFO.DI_DT = DOCTYPE.DT_KEY
    " . $WHERE . "    
GROUP BY 
    SM.SKU_CODE,
    SM.SKU_NAME,
    WH.WH_CODE,
    WL.WL_CODE,
    UQ.UTQ_NAME
HAVING 
    SUM(SKM.SKM_QTY) > 0
ORDER BY 
    SM.SKU_CODE,
    WH.WH_CODE,
    WL.WL_CODE
";

/*
$my_file = fopen("D-AAA.txt", "w") or die("Unable to open file!");
fwrite($my_file, $String_Sql);
fclose($my_file);
*/

$data = "รหัสสินค้า,สินค้า,WH_CODE,WL_CODE,หน่วยนับ,จำนวน\n";

$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();

$loop = 0;

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

    $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
    $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
    $data .= str_replace(",", "^", $row['WH_CODE']) . ",";
    $data .= str_replace(",", "^", $row['WL_CODE']) . ",";
    $data .= str_replace(",", "^", $row['UNIT_NAME']) . ",";
    $data .= $row['SUM_QTY'] . "\n";

}

// $data = iconv("utf-8", "tis-620", $data);
$data = iconv("utf-8", "windows-874//IGNORE", $data);
echo $data;

exit();



//$my_file = fopen("D-AAA.txt", "w") or die("Unable to open file!");
//fwrite($my_file, $String_Sql . " / " . $WH_CODE . " - " . $WH_NAME);
//fclose($my_file);

/*
$String_Sql =" SELECT SKU_CODE,SKU_NAME,UTQ_NAME,SUM(QTY) AS SUM_QTY from v_stock_movement
WHERE WH_CODE = '" . $WH_CODE . "'
GROUP BY SKU_CODE,SKU_NAME ,UTQ_NAME
ORDER BY SKU_CODE ";
*/