<?php
date_default_timezone_set('Asia/Bangkok');

$product_cat = $_POST["product_cat"];
$branch = $_POST["branch"];
$sql_find_tel = "";
$query_cat = "";
$filename = $branch . "-" . "Data_Sale_Daily-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

include('../config/connect_sqlserver.php');
include('../cond_file/doc_info_sale_daily_cp.php');

switch ($branch) {
    case "CP-340":
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('30','CS4','CS5','DS4','IS3','IS4','ISC3','ISC4')) ";
        break;
    case "CP-BY":
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('CS.8','CS.9','IC.3','IC.4','IS.3','IS.4','S.5','S.6')) ";
        break;
    case "CP-RP":
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('CS.6','CS.7','IC.1','IC.2','IS.1','IS.2','S.1','S.2')) ";
        break;
    case "CP-BB":
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('CS.2','CS.3','IC.5','IC.6','IS.5','IS.6','S.3','S.4')) ";
        break;
    case "ALL":
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('30','CS4','CS5','DS4','IS3','IS4','ISC3','ISC4',
        'CS.8','CS.9','IC.3','IC.4','IS.3','IS.4','S.5','S.6',
        'CS.6','CS.7','IC.1','IC.2','IS.1','IS.2','S.1','S.2',
        'CS.2','CS.3','IC.5','IC.6','IS.5','IS.6','S.3','S.4')) ";
        break;
}

$doc_date_start = substr($_POST['doc_date_start'], 6, 4) . "/" . substr($_POST['doc_date_start'], 3, 2) . "/" . substr($_POST['doc_date_start'], 0, 2);
$doc_date_to = substr($_POST['doc_date_to'], 6, 4) . "/" . substr($_POST['doc_date_to'], 3, 2) . "/" . substr($_POST['doc_date_to'], 0, 2);

$month_arr = array(
    "01" => "มกราคม",
    "02" => "กุมภาพันธ์",
    "03" => "มีนาคม",
    "04" => "เมษายน",
    "05" => "พฤษภาคม",
    "06" => "มิถุนายน",
    "07" => "กรกฎาคม",
    "08" => "สิงหาคม",
    "09" => "กันยายน",
    "10" => "ตุลาคม",
    "11" => "พฤศจิกายน",
    "12" => "ธันวาคม"
);

/*
$month = substr($_POST['doc_date_start'], 3, 2);
$month_name = $month_arr[$month];
$year = substr($_POST['doc_date_to'], 6, 4);
*/

if ($product_cat !== '-') {
    $query_cat = " AND ICCAT_CODE = '" . $product_cat . "' ";
}

$String_Sql = $select_query_daily . $select_query_daily_cond . " AND DI_DATE BETWEEN '" . $doc_date_start . "' AND '" . $doc_date_to . "' "
    . $query_daily_cond_ext . $query_cat
    . $select_query_daily_order;

/*
$my_file = fopen("sql_string.txt", "w") or die("Unable to open file!");
fwrite($my_file, $String_Sql);
fclose($my_file);
*/

$data = "วันที่,เดือน,ปี,รหัสลูกค้า,รหัสสินค้า,รายละเอียดสินค้า,รายละเอียด,ยี่ห้อ,INV ลูกค้า,ชื่อลูกค้า,เบอร์โทรฯ,ผู้แทนขาย,จำนวน,ราคาขาย,ส่วนลดรวม,ส่วนลดต่อเส้น,มูลค่ารวม,ภาษี 7%,มูลค่ารวมภาษี,คลัง,เลขที่ใบจอง,วันเวลาเปิดบิล,,วันเวลาปิดบิล\n";

$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

    /*
        $sql_find_tel = "select TOP 1 * from ADDRBOOK
                        where ADDB_COMPANY LIKE '%" . $row['AR_NAME'] . "%'
                        and ADDB_PHONE IS NOT NULL ";
    */

    /*
        $sql_find_tel = "SELECT ARADDRESS.ARA_KEY,ADDRBOOK.ADDB_COMPANY,ADDB_PHONE, FROM  ARFILE
    LEFT JOIN ARADDRESS ON ARADDRESS.ARA_AR = ARFILE.AR_KEY AND ARADDRESS.ARA_DEFAULT = 'Y'
    LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
    WHERE AR_KEY = " . $row['AR_KEY'];
    */

    $sql_find_tel = "SELECT ARADDRESS.ARA_KEY,ADDRBOOK.ADDB_COMPANY,ADDB_PHONE FROM  ARADDRESS 
    LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
    WHERE ARADDRESS.ARA_AR = " . $row['AR_KEY'] . " AND ARADDRESS.ARA_DEFAULT = 'Y'";

    $tel = "";
    $query_tel = $conn_sqlsvr->prepare($sql_find_tel);
    $query_tel->execute();
    while ($rows = $query_tel->fetch(PDO::FETCH_ASSOC)) {
        $tel = $rows['ADDB_PHONE'] . " .";
    }

    $searchStrings = ["B", "Q"];
    $found = false;
    $str_reserve_id = "";
    $reserve_id = "";
    $rest = "";

    if (!empty($row['DI_REMARK'])) { // ตรวจสอบว่ามีค่าใน DI_REMARK หรือไม่
        foreach ($searchStrings as $searchString) {
            $pos1 = strpos($row['DI_REMARK'], $searchString); // หาตำแหน่งของ B หรือ Q
            $pos2 = strpos($row['DI_REMARK'], ":"); // หาตำแหน่งของ ":"

            // ตรวจสอบว่าพบตัวอักษรและ ":" ในข้อความ และ ":" อยู่หลัง B หรือ Q
            if ($pos1 !== false && $pos2 !== false && $pos2 > $pos1) {
                $pos3 = $pos2 - $pos1; // คำนวณความยาวระหว่างตำแหน่ง
                $str_reserve_id = substr($row['DI_REMARK'], $pos1, $pos3); // ตัดข้อความ
                $found = true; // ตั้งค่าว่าพบข้อมูลแล้ว
                break; // หยุดการค้นหาทันทีเมื่อเจอ
            }
        }

        if ($found) {
            $rest .= "ผลลัพธ์: " . $str_reserve_id . "\n\r"; // แสดงข้อความที่ตัดได้

            // Query แบบปลอดภัย
            $sql_find_reserve = "SELECT DOCINFO.DI_REF,
            FORMAT(CAST(DOCINFO.DI_CRE_DATE AS datetime), 'dd/MM/yyyy, HH:mm:ss') AS START_BILL 
            FROM DOCINFO
            WHERE DOCINFO.DI_REF = :str_reserve_id"; // ใช้ :str_reserve_id เป็น placeholder

            $query_reserve = $conn_sqlsvr->prepare($sql_find_reserve);
            $query_reserve->bindParam(':str_reserve_id', $str_reserve_id, PDO::PARAM_STR); // Bind ตัวแปร
            $query_reserve->execute();

            while ($rows1 = $query_reserve->fetch(PDO::FETCH_ASSOC)) {
                $reserve_id = $rows1['START_BILL'];
            }
        } else {
            $rest .= "ไม่พบข้อมูลที่ต้องการในข้อความ" . "\n\r";
            $reserve_id = "";
        }
    } else {
        $rest .= "ข้อความ DI_REMARK ไม่มีข้อมูล" . "\n\r";
        $reserve_id = "";
    }

    /*
        $my_file = fopen("sql_string.txt", "w") or die("Unable to open file!");
        fwrite($my_file, $str_reserve_id . " | " . $rest);
        fclose($my_file);
    */

    $month = substr($row['DI_DATE'], 3, 2);
    $month_name = $month_arr[$month];
    $year = substr($row['DI_DATE'], 6, 4);


    $data .= " " . $row['DI_DATE'] . ",";
    $data .= " " . $month_name . ",";
    $data .= " " . $year . ",";

    $data .= str_replace(",", "^", $row['AR_CODE']) . ",";
    $data .= str_replace(",", "^", $row['SKU_CODE']) . ",";
    $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";

    $data .= str_replace(",", "^", $row['ICCAT_NAME']) . ",";
    //$data .= " " . ",";
    $data .= str_replace(",", "^", $row['BRN_NAME']) . ",";
    $data .= str_replace(",", "^", $row['DI_REF']) . ",";
    $data .= str_replace(",", "^", $row['AR_NAME']) . ",";
    $data .= str_replace(",", "^", $tel) . ",";
    $data .= str_replace(",", "^", $row['SLMN_CODE']) . ",";


    $TRD_QTY = $row['TRD_Q_FREE'] > 0 ? $row['TRD_QTY'] = $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];

    $TRD_QTY = $row['TRD_QTY'];
    $TRD_U_PRC = $row['TRD_U_PRC'];
    $TRD_DSC_KEYINV = $row['TRD_DSC_KEYINV'];
    $TRD_B_SELL = $row['TRD_G_SELL'];
    $TRD_B_VAT = $row['TRD_G_VAT'];
    $TRD_G_KEYIN = $row['TRD_G_KEYIN'];


    //$my_file = fopen("D-sac_str_return.txt", "w") or die("Unable to open file!");
    //fwrite($my_file, "Data " . " = " . $TRD_QTY . " | " . $TRD_U_PRC . " | "
    //. $TRD_DSC_KEYINV . " | " . $TRD_B_SELL . " | " . $TRD_B_VAT . " | " . $TRD_G_KEYIN);
    //fclose($my_file);

    $data .= $TRD_QTY . ",";
    $data .= $TRD_U_PRC . ",";
    $data .= $TRD_DSC_KEYINV . ",";
    $data .= " " . ",";
    $data .= $TRD_B_SELL . ",";
    $data .= $TRD_B_VAT . ",";
    $data .= $TRD_G_KEYIN . ",";
    $data .= str_replace(",", "^", $row['WL_CODE']) . ",";
    $data .= $str_reserve_id . ",";
    $data .= $reserve_id . ",";
    $data .= $row['DI_PRN_DATE_CHK1'] . "\n";

}

// $data = iconv("utf-8", "tis-620", $data);
$data = iconv("utf-8", "windows-874//IGNORE", $data);
echo $data;

exit();