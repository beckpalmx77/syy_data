<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../config/connect_sqlserver.php';

// รับค่าจากฟอร์ม
$ADDB_SEARCH = $_POST['ADDB_SEARCH'] ?? '';

// สร้างคำสั่ง SQL
$query = "
SELECT 
    TRANSTKD.TRD_KEY, 
    ADDRBOOK.ADDB_KEY, 
    ADDRBOOK.ADDB_BRANCH, 
    ADDRBOOK.ADDB_SEARCH,
    ADDRBOOK.ADDB_ADDB_1, 
    ADDRBOOK.ADDB_ADDB_2, 
    ADDRBOOK.ADDB_ADDB_3,
    ADDRBOOK.ADDB_COMPANY,
    ADDRBOOK.ADDB_PHONE,
    DOCINFO.DI_REF, 
    DOCINFO.DI_DATE,
    DAY(DOCINFO.DI_DATE) AS DI_DAY,
    MONTH(DOCINFO.DI_DATE) AS DI_MONTH,
    YEAR(DOCINFO.DI_DATE) AS DI_YEAR,
    TRANSTKH.TRH_DI,
    TRANSTKH.TRH_SHIP_ADDB,
    SKUMASTER.SKU_CODE,
    SKUMASTER.SKU_NAME,
    TRANSTKD.TRD_QTY,
    TRANSTKD.TRD_Q_FREE,
    TRANSTKD.TRD_U_PRC,
    TRANSTKD.TRD_B_SELL,
    TRANSTKD.TRD_B_VAT,
    TRANSTKD.TRD_B_AMT
FROM 
    ADDRBOOK
    JOIN ARADDRESS ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
    JOIN TRANSTKH ON TRANSTKH.TRH_SHIP_ADDB = ADDRBOOK.ADDB_KEY
    JOIN ARDETAIL ON ARDETAIL.ARD_AR = ARADDRESS.ARA_AR
    JOIN DOCINFO ON DOCINFO.DI_KEY = ARDETAIL.ARD_DI AND DOCINFO.DI_KEY = TRANSTKH.TRH_DI
    JOIN TRANSTKD ON TRANSTKH.TRH_KEY = TRANSTKD.TRD_TRH
    JOIN SKUMASTER ON TRANSTKD.TRD_SKU = SKUMASTER.SKU_KEY
WHERE 
    ADDRBOOK.ADDB_SEARCH LIKE :ADDB_SEARCH
ORDER BY 
    ADDRBOOK.ADDB_COMPANY, SKUMASTER.SKU_CODE
";

/*
$myfile = fopen("his-param.txt", "w") or die("Unable to open file!");
fwrite($myfile,  $query);
fclose($myfile);
*/

$stmt = $conn_sqlsvr->prepare($query);
$stmt->bindValue(':ADDB_SEARCH', "%$ADDB_SEARCH%", PDO::PARAM_STR);

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// แปลงผลลัพธ์เป็น JSON
$json_results = json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// กำหนดชื่อไฟล์สำหรับบันทึกผลลัพธ์
$output_file = 'output_results.json';

// บันทึกผลลัพธ์ลงไฟล์
//file_put_contents($output_file, $json_results);

// ส่งผลลัพธ์เป็น JSON กลับไปยังผู้เรียก
echo $json_results;

echo json_encode($results);

