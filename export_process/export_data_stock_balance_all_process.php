<?php
date_default_timezone_set('Asia/Bangkok');

include('../config/connect_sqlserver.php');

// HEADER สำหรับดาวน์โหลด CSV
$WH_CODE = $_POST['WH_CODE'] ?? '';
$filename = "Data_Stock_All_Balance-" . $WH_CODE . "-" . date('Ymd-His') . ".csv";
header('Content-type: text/csv; charset=UTF-8');
header('Content-Encoding: UTF-8');
header("Content-Disposition: attachment; filename=\"$filename\"");

$where = [];

// ฟังก์ชันเพื่อ escape ค่าและ wrap ด้วย quote
function quoteArray($arr, $conn) {
    return array_map(function ($v) use ($conn) {
        return $conn->quote(trim($v));
    }, $arr);
}

// WH_CODE
if (!empty($_POST['WH_CODE'])) {
    $where[] = "WH.WH_CODE IN (" . $conn_sqlsvr->quote($_POST['WH_CODE']) . ")";
}

// ICCAT
if (!empty($_POST['icc_codes'])) {
    $in = implode(',', quoteArray($_POST['icc_codes'], $conn_sqlsvr));
    $where[] = "ICCAT.ICCAT_CODE IN ($in)";
}

// BRAND
if (!empty($_POST['brn_codes'])) {
    $in = implode(',', quoteArray($_POST['brn_codes'], $conn_sqlsvr));
    $where[] = "BRAND.BRN_CODE IN ($in)";
}

// WARELOCATION
if (!empty($_POST['wl_codes'])) {
    $in = implode(',', quoteArray($_POST['wl_codes'], $conn_sqlsvr));
    $where[] = "WL.WL_CODE IN ($in)";
}

// รวม WHERE ทั้งหมด
$WHERE_SQL = "";
if (!empty($where)) {
    $WHERE_SQL = "WHERE " . implode(' AND ', $where);
}

// SQL Query หลัก
$String_Sql = "
SELECT 
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
    $WHERE_SQL
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

//file_put_contents('sql_debug_front.log', print_r($_POST, true) . PHP_EOL, FILE_APPEND);
//file_put_contents('sql_debug_back.log', $String_Sql . PHP_EOL, FILE_APPEND);

// ดึงข้อมูล
$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();



// สร้าง CSV
$data = "รหัสสินค้า,สินค้า,WH_CODE,WL_CODE,หน่วยนับ,จำนวน\n";
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
    $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
    $data .= str_replace(",", "^", $row['WH_CODE']) . ",";
    $data .= str_replace(",", "^", $row['WL_CODE']) . ",";
    $data .= str_replace(",", "^", $row['UNIT_NAME']) . ",";
    $data .= $row['SUM_QTY'] . "\n";
}

// แปลง encoding เป็น windows-874 เพื่อเปิดใน Excel ได้
echo iconv("utf-8", "windows-874//IGNORE", $data);
exit();
