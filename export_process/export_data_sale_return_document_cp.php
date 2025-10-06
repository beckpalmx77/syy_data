<?php
date_default_timezone_set('Asia/Bangkok');

$branch = $_POST["branch"];

$filename = $branch . "-" . "Data_Sale_Daily-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);

include('../config/connect_sqlserver.php');
include('../cond_file/doc_info_sale_daily_cp.php');

$customer_except_list = array("SAC.0000328");

$DT_DOCCODE_MINUS1 = "IS";
$DT_DOCCODE_MINUS2 = "IIS";
$DT_DOCCODE_MINUS3 = "IC";

// Array ข้อมูลที่คุณให้มา
$str_doc1 = array("CS01", "CS09", "CV01", "CV09", "DS01", "DS08", "IV01", "IV08", "ISC1", "ISC7", "ISC8", "ICO1", "ICO8", "IS1", "IS7", "ISO1", "ISO7", "IC10");
$str_doc2 = array("CS08", "CV07", "DS07", "IV07", "ISC6", "ICO6", "IS6", "ISO6");
$str_doc3 = array("CS02", "CV02", "DS02", "IV02", "ICO2", "IS2", "ISO2", "ISC2");
$str_doc4 = array("CS03", "CV03", "DS03", "IV03", "ISC3", "ICO3", "IS3", "ISO3");
$str_doc5 = array("CS04", "CV04", "DS04", "IV04", "ISC4", "ICO4", "IS4", "ISO4");
$str_doc6 = array("CS05", "CV05", "DS05", "IV05", "ISC5", "ICO5", "IS5", "ISO5");
$str_doc7 = array("CS14", "CV014", "DS12", "IV12", "ICO9", "ISC9", "IS8", "ISO8");
$str_doc8 = array("CS15", "CV15", "DS13", "IV13", "IC11", "IS10", "IS9", "ISO9");

// แปลง Array ให้เป็น String สำหรับ SQL IN clause
$doc1_str = implode("','", $str_doc1);
$doc2_str = implode("','", $str_doc2);
$doc3_str = implode("','", $str_doc3);
$doc4_str = implode("','", $str_doc4);
$doc5_str = implode("','", $str_doc5);
$doc6_str = implode("','", $str_doc6);
$doc7_str = implode("','", $str_doc7);
$doc8_str = implode("','", $str_doc8);

// สำหรับกรณี 'ALL' ให้รวมทั้งหมด
$all_docs = array_merge($str_doc1, $str_doc2, $str_doc3, $str_doc4); // ดูจากตัวอย่างเดิมน่าจะรวมแค่ 4 ตัวแรก
$all_docs_str = implode("','", $all_docs);

switch ($branch) {
    case "SYY01":
        // ใช้ข้อมูลจาก $str_doc1
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc1_str}')) ";
        break;
    case "SYY02":
        // ใช้ข้อมูลจาก $str_doc2
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc2_str}')) ";
        break;
    case "SYY03":
        // ใช้ข้อมูลจาก $str_doc3
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc3_str}')) ";
        break;
    case "SYY04":
        // ใช้ข้อมูลจาก $str_doc4
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc4_str}')) ";
        break;
    case "SYY05":
        // ใช้ข้อมูลจาก $str_doc5
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc5_str}')) ";
        break;
    case "SYY06":
        // ใช้ข้อมูลจาก $str_doc6
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc6_str}')) ";
        break;
    case "SYY07":
        // ใช้ข้อมูลจาก $str_doc7
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc7_str}')) ";
        break;
    case "SYY08":
        // ใช้ข้อมูลจาก $str_doc8
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$doc8_str}')) ";
        break;
    case "ALL":
        $query_daily_cond_ext = " AND (DOCTYPE.DT_DOCCODE in ('{$all_docs_str}')) ";
        break;
}

$doc_date_start = substr($_POST['doc_date_start'], 6, 4) . "/" . substr($_POST['doc_date_start'], 3, 2) . "/" . substr($_POST['doc_date_start'], 0, 2);
$doc_date_to = substr($_POST['doc_date_to'], 6, 4) . "/" . substr($_POST['doc_date_to'], 3, 2) . "/" . substr($_POST['doc_date_to'], 0, 2);

$month_arr=array(
    "01"=>"มกราคม",
    "02"=>"กุมภาพันธ์",
    "03"=>"มีนาคม",
    "04"=>"เมษายน",
    "05"=>"พฤษภาคม",
    "06"=>"มิถุนายน",
    "07"=>"กรกฎาคม",
    "08"=>"สิงหาคม",
    "09"=>"กันยายน",
    "10"=>"ตุลาคม",
    "11"=>"พฤศจิกายน",
    "12"=>"ธันวาคม"
);

/*
$month = substr($_POST['doc_date_start'], 3, 2);
$month_name = $month_arr[$month];
$year = substr($_POST['doc_date_to'], 6, 4);
*/

$String_Sql = $select_query_daily . $select_query_daily_cond . " AND DI_DATE BETWEEN '" . $doc_date_start . "' AND '" . $doc_date_to . "' "
    . $query_daily_cond_ext
    . $select_query_daily_order;

/*
$my_file = fopen("a-retail.txt", "w") or die("Unable to open file!");
fwrite($my_file, $String_Sql);
fclose($my_file);
*/

$data = "วันที่,เดือน,ปี,รหัสลูกค้า,รหัสสินค้า,รายละเอียดสินค้า,รายละเอียด,ยี่ห้อ,INV ลูกค้า,ชื่อลูกค้า,ผู้แทนขาย,จำนวน,ราคาขาย,ส่วนลดรวม,ส่วนลดต่อเส้น,มูลค่ารวม,ภาษี 7%,มูลค่ารวมภาษี,คลัง\n";

$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {


    if ($row['SKU_CODE']!=="9002") {

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
        $data .= str_replace(",", "^", $row['SLMN_CODE']) . ",";


        $TRD_QTY = $row['TRD_Q_FREE'] > 0 ? $row['TRD_QTY'] = $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];

        if ((strpos($row['DT_DOCCODE'], $DT_DOCCODE_MINUS1) !== false) || (strpos($row['DT_DOCCODE'], $DT_DOCCODE_MINUS2) !== false) || (strpos($row['DT_DOCCODE'], $DT_DOCCODE_MINUS3) !== false)) {
            $TRD_QTY = "-" . $row['TRD_QTY'];
            $TRD_U_PRC = "-" . $row['TRD_U_PRC'];
            $TRD_DSC_KEYINV = "-" . $row['TRD_DSC_KEYINV'];
            $TRD_B_SELL = "-" . $row['TRD_G_SELL'];
            $TRD_B_VAT = "-" . $row['TRD_G_VAT'];
            $TRD_G_KEYIN = "-" . $row['TRD_G_KEYIN'];
        } else {
            $TRD_QTY = $row['TRD_QTY'];
            $TRD_U_PRC = $row['TRD_U_PRC'];
            $TRD_DSC_KEYINV = $row['TRD_DSC_KEYINV'];
            $TRD_B_SELL = $row['TRD_G_SELL'];
            $TRD_B_VAT = $row['TRD_G_VAT'];
            $TRD_G_KEYIN = $row['TRD_G_KEYIN'];
        }

        if (in_array($row['AR_CODE'], $customer_except_list)) {
            $TRD_B_SELL = "0";
            $TRD_B_VAT = "0";
            $TRD_G_KEYIN = "0";
        }


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
        $data .= str_replace(",", "^", $row['WL_CODE']) . "\n";
    }

}

$data = iconv("utf-8", "tis-620//IGNORE", $data);
echo $data;

exit();