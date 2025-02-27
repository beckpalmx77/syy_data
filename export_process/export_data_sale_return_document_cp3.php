<?php
date_default_timezone_set('Asia/Bangkok');

$customer_name = $_POST["customer_name"] ?? '';
$car_no = $_POST["car_no"] ?? '';

$filename = "Data_Customer-Service-" . date('Y-m-d_H-i-s') . ".csv";

header('Content-Type: text/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filename");

// เชื่อมต่อฐานข้อมูล
include('../config/connect_sqlserver.php');
include('../cond_file/query_customer_history_service.php');
include('../util/month_util.php');

// ใช้ bindParam เพื่อความปลอดภัย
$sql_and = " AND ADDRBOOK.ADDB_COMPANY LIKE :customer_name AND ADDRBOOK.ADDB_SEARCH LIKE :car_no ";
$String_Sql = $str_sql_comm . $sql_and . $str_sql_order;

$query = $conn_sqlsvr->prepare($String_Sql);
$query->bindValue(':customer_name', '%' . $customer_name . '%', PDO::PARAM_STR);
$query->bindValue(':car_no', '%' . $car_no . '%', PDO::PARAM_STR);
$query->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($query->rowCount() == 0) {
    die("❌ ไม่พบข้อมูลในฐานข้อมูล");
}

$data = "ลำดับที่,วัน,เดือน,ปี,เลขที่เอกสาร,รหัสลูกค้า,ชื่อลูกค้า,หมายเลขโทรศัพท์,ทะเบียนรถ,ยี่ห้อรถ/รุ่น,เลขไมล์,รหัสสินค้า,ชื่อสินค้า,จำนวน,จำนวนเงิน\n";

$line = 0;
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $line++;
    $month_name = $month_arr[$row['DI_MONTH']];
    $year = $row['DI_YEAR'];
    $TRD_QTY = ($row['TRD_Q_FREE'] > 0) ? $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];

    $sql_cust_string = "
        SELECT ADDRBOOK.ADDB_PHONE
        FROM ARADDRESS
        LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
        WHERE ADDRBOOK.ADDB_COMPANY LIKE :company AND ARADDRESS.ARA_DEFAULT = 'Y' 
    ";
    $statement_cust_sqlsvr = $conn_sqlsvr->prepare($sql_cust_string);
    $statement_cust_sqlsvr->bindValue(':company', '%' . $row['ADDB_COMPANY'] . '%', PDO::PARAM_STR);
    $statement_cust_sqlsvr->execute();

    $addb_phone = "";
    if ($result_sqlsvr_cust = $statement_cust_sqlsvr->fetch(PDO::FETCH_ASSOC)) {
        $addb_phone = $result_sqlsvr_cust['ADDB_PHONE'] === null ? "" : $result_sqlsvr_cust['ADDB_PHONE'];
    }

    $sql_cust_string2 = "
    SELECT TOP 1 ARFILE.AR_CODE
    FROM ARFILE        
    WHERE ARFILE.AR_NAME = :company;
";
    $statement_cust_sqlsvr2 = $conn_sqlsvr->prepare($sql_cust_string2);
    $statement_cust_sqlsvr2->bindValue(':company', $row['ADDB_COMPANY'], PDO::PARAM_STR);
    $statement_cust_sqlsvr2->execute();

    $AR_CODE = "";
    if ($result_sqlsvr_cust2 = $statement_cust_sqlsvr2->fetch(PDO::FETCH_ASSOC)) {
        $AR_CODE = $result_sqlsvr_cust2['AR_CODE'] === null ? "" : $result_sqlsvr_cust2['AR_CODE'];
    }

    
    $ADDB_COMPANY = $row['ADDB_COMPANY'] === null ? "" : $row['ADDB_COMPANY'];
    $addb_phone = $result_sqlsvr_cust['ADDB_PHONE'] === null ? "" : $result_sqlsvr_cust['ADDB_PHONE'];
    $ADDB_ADDB_1 = $row['ADDB_ADDB_1'] === null ? "" : $row['ADDB_ADDB_1'];
    $ADDB_ADDB_2 = $row['ADDB_ADDB_2'] === null ? "" : $row['ADDB_ADDB_2'];
    $ADDB_ADDB_3 = $row['ADDB_ADDB_3'] === null ? "" : $row['ADDB_ADDB_3'];
    $ADDB_BRANCH = $row['ADDB_BRANCH'] === null ? "" : $row['ADDB_BRANCH'];


    $data .= "$line,{$row['DI_DAY']},$month_name,$year,";
    $data .= str_replace(",", " ", $row['DI_REF']) . ",";
    $data .= str_replace(",", " ", $AR_CODE) . ",";
    $data .= str_replace(",", " ", $ADDB_COMPANY) . ",";
    $data .= str_replace(",", " ", $addb_phone) . ",";
    $data .= str_replace(",", " ", $ADDB_BRANCH) . ",";
    $data .= str_replace(",", " ", $ADDB_ADDB_1 . " " . $ADDB_ADDB_2) . ",";
    $data .= str_replace(",", " ", $ADDB_ADDB_3) . ",";
    $data .= str_replace(",", " ", $row['SKU_CODE']) . ",";
    $data .= str_replace(",", " ", $row['SKU_NAME']) . ",";
    $data .= str_replace(",", " ", $TRD_QTY) . ",";
    $data .= str_replace(",", " ", $row['TRD_B_AMT']) . "\n";
}

// ตรวจสอบว่าไฟล์ถูกสร้างขึ้นหรือไม่
//file_put_contents("test.csv", $data);
echo "\xEF\xBB\xBF"; // BOM UTF-8
echo $data;

exit();
