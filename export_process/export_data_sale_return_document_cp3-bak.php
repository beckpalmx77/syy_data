<?php
date_default_timezone_set('Asia/Bangkok');
// เชื่อมต่อฐานข้อมูล
include('../config/connect_sqlserver.php');
include('../cond_file/query_customer_history_service.php');

// รับค่าจาก POST
$customer_name = $_POST["customer_name"] ?? '';
$car_no = $_POST["car_no"] ?? '';
// สร้างชื่อไฟล์
$filename = "Data_Customer_History-" . date('Y-m-d_H-i-s') . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

// แปลงเดือนเป็นภาษาไทย
$month_arr = array(
    "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม",
    "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน",
    "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน",
    "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
);

// ตรวจสอบและเพิ่มเงื่อนไข

//$query_cat = $product_cat !== '-' ? " AND ICCAT_CODE = :product_cat " : '';

$sql_and = " AND ADDRBOOK.ADDB_COMPANY LIKE :customer_name AND ADDRBOOK.ADDB_SEARCH LIKE :car_no ";
$String_Sql = $str_sql_comm . $sql_and . $str_sql_order;

/*
$my_file = fopen("Exp-sac_str_return.txt", "w") or die("Unable to open file!");
fwrite($my_file, $String_Sql);
fclose($my_file);
*/

// สร้างหัวตาราง CSV
$data = "ลำดับ,วัน,เดือน,ปี,เลขที่เอกสาร,ชื่อลูกค้า,หมายเลขโทรศัพท์,ทะเบียนรถ,ยี่ห้อรถ/รุ่น,เลขไมล์,รหัสสินค้า,ชื่อสินค้า,จำนวน,จำนวนเงิน\n";

// เตรียม Query
$query = $conn_sqlsvr->prepare($String_Sql);
$query->bindValue(':customer_name', '%' . $customer_name . '%');
$query->bindValue(':car_no', '%' . $car_no . '%');

//if ($product_cat !== '-') {
    //$query->bindValue(':product_cat', $product_cat);
//}

// รัน Query
try {
    $query->execute();
    $line = 0;

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $line++;

        $month_name = $month_arr[$row['DI_MONTH']];
        $year = $row['DI_YEAR'];

        $TRD_QTY = $row['TRD_Q_FREE'] > 0 ? $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];
/*
        $my_file = fopen("Exp2-sac_str_return.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $month_name);
        fclose($my_file);
*/

        $sql_cust_string = "
            SELECT ADDRBOOK.ADDB_PHONE,ARADDRESS.ARA_ADDB
            FROM ARADDRESS
            LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
            WHERE ADDRBOOK.ADDB_COMPANY LIKE '%" . $row['ADDB_COMPANY'] . "%' AND ARADDRESS.ARA_DEFAULT = 'Y' ";
        $statement_cust_sqlsvr = $conn_sqlsvr->prepare($sql_cust_string);
        $statement_cust_sqlsvr->execute();
        while ($result_sqlsvr_cust = $statement_cust_sqlsvr->fetch(PDO::FETCH_ASSOC)) {
            $addb_phone = $result_sqlsvr_cust['ADDB_PHONE'];
        }

        $data .= "$line," . $row['DI_DAY'] . ",$month_name,$year,";
        $data .= str_replace(",", "^", $row['ADDB_COMPANY']) . ",";
        $data .= str_replace(",", "^", $addb_phone) . ",";
        $data .= str_replace(",", "^", $row['ADDB_BRANCH']) . ",";
        $data .= str_replace(",", "^", $row['ADDB_ADDB_1'] . " " . $row['ADDB_ADDB_3']) . ",";
        $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
        $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
        $data .= str_replace(",", "^", $TRD_QTY) . ",";
        $data .= str_replace(",", "^",$row['TRD_B_AMT']) . "\n";
    }

    // แปลงข้อมูล
    //$data = iconv("utf-8", "windows-874//IGNORE", $data);
    $data = iconv("utf-8", "tis-620", $data);

    echo $data;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

exit();
