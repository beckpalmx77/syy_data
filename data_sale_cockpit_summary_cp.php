<?php

include("config/connect_db.php");

$customer_except_list = array("SAC.0000328");

$doc_date_start = substr($_POST['doc_date_start'], 6, 4) . "/" . substr($_POST['doc_date_start'], 3, 2) . "/" . substr($_POST['doc_date_start'], 0, 2);
$doc_date_to = substr($_POST['doc_date_to'], 6, 4) . "/" . substr($_POST['doc_date_to'], 3, 2) . "/" . substr($_POST['doc_date_to'], 0, 2);
$BRANCH = $_POST['branch'];

$STR_WHERE = "WHERE STR_TO_DATE(DI_DATE, '%d/%m/%Y')  BETWEEN '" . $doc_date_start . "' AND '" . $doc_date_to . "' AND BRANCH = '" . $BRANCH . "' ";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta date="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <script src="js/jquery-3.6.0.js"></script>
    <!--script src="js/chartjs-2.9.0.js"></script-->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome/css/font-awesome.css">

    <link href='vendor/calendar/main.css' rel='stylesheet'/>
    <script src='vendor/calendar/main.js'></script>
    <script src='vendor/calendar/locales/th.js'></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script src='js/util.js'></script>

    <title>สงวนออโต้คาร์</title>

</head>

<body onload="">

<p class="card">
<div class="card-header bg-primary text-white">
    <i class="fa fa-signal" aria-hidden="true"></i> ยอดขายรวมรายวัน Cockpit (สรุป)
</div>

<div class="card-body">
    <a id="myLink" href="#" onclick="PrintPage();"><i class="fa fa-print"></i> พิมพ์</a>
</div>

<div class="card">
    <div class="card-body">
        <form id="myform" name="myform" method="post">
            <input type="hidden" id="branch" name="branch">
            <input type="hidden" name="product_group" id="product_group" class="form-control" value="">
            <input type="hidden" name="product_group_name" id="product_group_name" class="form-control" value="">

        </form>
        <table id="example" class="display table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>วันที่</th>
                <th>รหัสลูกค้า</th>
                <th>ชื่อลูกค้า</th>
                <th>รหัสสินค้า</th>
                <th>รายละเอียดสินค้า</th>
                <th>INV ลูกค้า</th>
                <th>ผู้แทนขาย</th>
                <th>จำนวน</th>
                <th>ราคาขาย</th>
                <th>ส่วนลดรวม</th>
                <th>ส่วนลดต่อเส้น</th>
                <th>มูลค่ารวม</th>
                <th>ภาษี 7%</th>
                <th>มูลค่ารวมภาษี</th>
                <th>คลัง</th>
            </tr>
            </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <?php
            $sql_summary = "SELECT * FROM  ims_product_sale_cockpit " . $STR_WHERE
                . " ORDER BY DI_DATE ASC, DI_REF ASC , AR_CODE ASC, TRD_SEQ ASC ";
            $statement_summary = $conn->query($sql_summary);
            $results_summary = $statement_summary->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results_summary

            as $row_summary) {

            $TRD_QTY = $row_summary['TRD_Q_FREE'] > 0 ? $row_summary['TRD_QTY'] = $row_summary['TRD_QTY'] + $row_summary['TRD_Q_FREE'] : $row_summary['TRD_QTY'];

            if ((strpos($row_summary['DT_DOCCODE'], $DT_DOCCODE_MINUS1) !== false) || (strpos($row_summary['DT_DOCCODE'], $DT_DOCCODE_MINUS2) !== false) || (strpos($row_summary['DT_DOCCODE'], $DT_DOCCODE_MINUS3) !== false)) {
                $TRD_QTY = "-" . $row_summary['TRD_QTY'];
                $TRD_U_PRC = "-" . $row_summary['TRD_U_PRC'];
                $TRD_DSC_KEYINV = "-" . $row_summary['TRD_DSC_KEYINV'];
                $TRD_B_SELL = "-" . $row_summary['TRD_G_SELL'];
                $TRD_B_VAT = "-" . $row_summary['TRD_G_VAT'];
                $TRD_G_KEYIN = "-" . $row_summary['TRD_G_KEYIN'];
            } else {
                $TRD_QTY = $row_summary['TRD_QTY'];
                $TRD_U_PRC = $row_summary['TRD_U_PRC'];
                $TRD_DSC_KEYINV = $row_summary['TRD_DSC_KEYINV'];
                $TRD_B_SELL = $row_summary['TRD_G_SELL'];
                $TRD_B_VAT = $row_summary['TRD_G_VAT'];
                $TRD_G_KEYIN = $row_summary['TRD_G_KEYIN'];
            }

            if (in_array($row_summary['AR_CODE'], $customer_except_list)) {
                $TRD_B_SELL = "0";
                $TRD_B_VAT = "0";
                $TRD_G_KEYIN = "0";
            }

            ?>

            <tr>
                <td><?php echo htmlentities($row_summary['DI_DATE']); ?></td>
                <td><?php echo htmlentities($row_summary['AR_CODE']); ?></td>
                <td><?php echo htmlentities($row_summary['AR_NAME']); ?></td>
                <td><?php echo htmlentities($row_summary['SKU_CODE']); ?></td>
                <td><?php echo htmlentities($row_summary['SKU_NAME']); ?></td>
                <td><?php echo htmlentities($row_summary['DI_REF']); ?></td>
                <td><?php echo htmlentities($row_summary['SLMN_CODE']); ?></td>
                <td><?php echo htmlentities($TRD_QTY); ?></td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($TRD_U_PRC, 2)); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($TRD_DSC_KEYINV, 2)); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($TRD_B_SELL, 2)); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($TRD_B_VAT, 2)); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($TRD_G_KEYIN, 2)); ?></p>
                </td>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

</tbody>
</table>
</div>
</div>


</body>
</html>

