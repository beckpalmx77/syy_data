<?php
include('../config/connect_sqlserver.php');
include('../cond_file/doc_info_sale_daily_btc.php');
include('../util/month_util.php');

date_default_timezone_set('Asia/Bangkok');

$myCheckValue = $_POST["myCheckValue"];
$month = ($_POST["month"]==="" ? "1" : $_POST["month"]);
$year = ($_POST["year"]==="" ? "" : $_POST["year"]);

/*
$my_file = fopen("Sale_D-BTC.txt", "w") or die("Unable to open file!");
fwrite($my_file, $month . " - " .$year . " myCheck  = " . $myCheckValue);
fclose($my_file);
*/


$filename = "BTC_Total_Data_Sale" . "-" . date('m/d/Y H:i:s', time()) . ".csv";

@header('Content-type: text/csv; charset=UTF-8');
@header('Content-Encoding: UTF-8');
@header("Content-Disposition: attachment; filename=" . $filename);


if ($myCheckValue === 'Y') {
    $select_where_daily = " AND YEAR(DI_DATE)  = " . $year;
} else {
    $select_where_daily = " AND MONTH(DI_DATE) = " . $month . " AND YEAR(DI_DATE) = " . $year;
}

$String_Sql = $select_query_daily . $select_query_daily_cond .  " AND (DT_DOCCODE = 'IV3' OR DT_DOCCODE = 'CCS7') " . $select_where_daily
    . $select_query_daily_order;


/*
$my_file = fopen("Sql_Sale_D-BTC.txt", "w") or die("Unable to open file!");
fwrite($my_file, $String_Sql);
fclose($my_file);
*/

$data = "ยอดขายของ BTC\n";
$data .= "เลขที่เอกสาร,รหัสลูกค้า,ชื่อลูกค้า,ประเภท,รายละเอียด,มูลค่ารวม,เดือน,ปี\n";

$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

        if ($row['TRD_G_KEYIN']>0) {

            $month_name = $month_arr[$row['DI_MONTH']];

            $data .= str_replace(",", "^", $row['DI_REF']) . ",";
            $data .= str_replace(",", "^", $row['AR_CODE']) . ",";
            $data .= str_replace(",", "^", $row['AR_NAME']) . ",";
            $data .= str_replace(",", "^", $row['ICCAT_NAME']) . ",";
            $data .= str_replace(",", "^", $row['SKU_NAME']) . ",";
            $data .= $row['TRD_G_KEYIN'] . ",";
            $data .= $month_name . ",";
            $data .= $row['DI_YEAR'] . "\n";
        }
}

// $data = iconv("utf-8", "tis-620", $data);
$data = iconv("utf-8", "windows-874//IGNORE", $data);
echo $data;

exit();