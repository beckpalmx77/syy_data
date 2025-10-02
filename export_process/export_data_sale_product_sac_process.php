<?php
date_default_timezone_set('Asia/Bangkok');

// เปลี่ยนชื่อไฟล์ให้ไม่มี / เพราะใช้ไม่ได้ในชื่อไฟล์
$filename = "Data_Sale_Summary-SAC-" . date('Y-m-d_H-i-s') . ".csv";

// ตั้ง header สำหรับ CSV และรองรับภาษาไทย (TIS-620)
header('Content-Type: text/csv; charset=TIS-620');
header("Content-Disposition: attachment; filename=" . $filename);

// เชื่อมต่อฐานข้อมูล
include('../config/connect_db2.php');

// ตรวจสอบว่าได้รับข้อมูลจาก POST หรือไม่
if (!isset($_POST['month']) || !isset($_POST['year'])) {
    die("Missing required parameters");
}

$DI_MONTH = $_POST['month'];
$DI_YEAR = $_POST['year'];

// SQL แบบ Prepared Statement เพื่อความปลอดภัย
$sql_summary = "
SELECT 
    DI_YEAR,
    DI_MONTH,
    DI_MONTH_NAME,
    SKU_CODE,
    SKU_NAME,
    SUM(CAST(TRD_QTY AS DECIMAL(15, 2))) AS TRD_QTY,
    SUM(CAST(TRD_TOTAL_PRICE AS DECIMAL(15, 2))) AS TRD_TOTAL_PRICE
FROM 
    ims_data_sale_sac_all
WHERE 
    DI_MONTH = :month 
    AND DI_YEAR = :year 
    AND SKU_CODE REGEXP '^[^0-9]'
    AND TRD_TOTAL_PRICE REGEXP '^[0-9]+(\\.[0-9]+)?$'
GROUP BY 
    DI_YEAR, DI_MONTH, DI_MONTH_NAME , SKU_CODE, SKU_NAME
HAVING 
    TRD_QTY > 0
ORDER BY 
    TRD_QTY DESC
";

// เตรียมคำสั่ง SQL และ bind parameter
$query = $conn->prepare($sql_summary);
$query->bindParam(':month', $DI_MONTH);
$query->bindParam(':year', $DI_YEAR);
$query->execute();

// เตรียมข้อมูลสำหรับ export
$data = "เดือน,ปี,รหัสสินค้า,ชื่อสินค้า,จำนวนสินค้า,จำนวนเงิน\n";

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $data .= " " . $row['DI_MONTH_NAME'] . ",";
    $data .= " " . $row['DI_YEAR'] . ",";
    $data .= " " . $row['SKU_CODE'] . ",";
    $data .= " " . $row['SKU_NAME'] . ",";
    $data .= " " . $row['TRD_QTY'] . ",";
    $data .= " " . $row['TRD_TOTAL_PRICE'] . "\n";
}

// แปลง UTF-8 เป็น TIS-620 สำหรับ Excel
$data = iconv("UTF-8", "TIS-620//IGNORE", $data);
echo $data;

$conn = null;

exit();

