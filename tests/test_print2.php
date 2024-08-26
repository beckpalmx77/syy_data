<?php
//เรียกใช้ไฟล์ autoload.php ที่อยู่ใน Folder vendor
require_once '../vendor/autoload.php';
include('../config/connect_sqlserver.php');

ini_set("pcre.backtrack_limit", "999999999");

$WH_CODE = "T010";

$String_Sql = " SELECT SKU_CODE,SKU_NAME,UTQ_NAME,SUM(QTY) AS SUM_QTY  from v_stock_movement  
WHERE WH_CODE = '" . $WH_CODE . "'    
GROUP BY SKU_CODE,SKU_NAME ,UTQ_NAME ,WH_CODE
ORDER BY SKU_CODE ";


$query = $conn_sqlsvr->prepare($String_Sql);
$query->execute();
$loop = 0;
$content = "";

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $loop++;
    $content .= '<tr style="border:1px solid #000;">
				<td style="border-right:1px solid #000;padding:3px;text-align:left;"  >' . $loop . '</td>
				<td style="border-right:1px solid #000;padding:3px;text-align:left;" >' . $row['SKU_CODE'] . '</td>
				<td style="border-right:1px solid #000;padding:3px;text-align:left;" >' . $row['SKU_NAME'] . '</td>							
				<td style="border-right:1px solid #000;padding:3px;text-align:left;"  >' . $row['UTQ_NAME'] . '</td>
				<td style="border-right:1px solid #000;padding:3px;text-align:right;"  >' . number_format($row['SUM_QTY'], 2) . '</td>
			</tr>';
}

$mpdf = new \Mpdf\Mpdf();

$head = '
<style>
	body{
		font-family: "Garuda";//เรียกใช้font Garuda สำหรับแสดงผล ภาษาไทย
	}
</style>

<h2 style="text-align:center">ยอดคงเหลือสินค้า</h2>

<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:12pt;margin-top:8px;">
    <tr style="border:1px solid #000;padding:4px;">
        <td  style="border-right:1px solid #000;padding:4px;text-align:center;"   width="10%">ลำดับ</td>
        <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">รหัสสินค้า</td>        
        <td  width="45%" style="border-right:1px solid #000;padding:4px;text-align:center;">&nbsp;รายละเอียดสินค้า</td>        
        <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">หน่วยนับ</td>
        <td  style="border-right:1px solid #000;padding:4px;text-align:center;" width="15%">จำนวน</td>
    </tr>

</thead>
<tbody>';

$end = "</tbody>
</table>";

$mpdf->WriteHTML($head);

$mpdf->WriteHTML($content);

$mpdf->WriteHTML($end);

$mpdf->Output();