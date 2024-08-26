<?php

include('includes/Header.php');
include("config/connect_db.php");

$DI_MONTH = $_POST['month'];
$DI_YEAR = $_POST['year'];
$BRANCH = $_POST['branch'];
$month_name = "";

$sql_month = " SELECT * FROM ims_month where month = '" . $DI_MONTH . "'";
$stmt_month = $conn->prepare($sql_month);
$stmt_month->execute();
$MonthRecords = $stmt_month->fetchAll();
foreach ($MonthRecords as $row) {
    $month_name = $row["month_name"];
}

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

    <script src="js/myadmin.min.js"></script>

    <title>สงวนออโต้คาร์</title>

</head>

<body id="page-top">

<p class="card">
<div class="card-header bg-primary text-white">
    <i class="fa fa-signal" aria-hidden="true"></i> ยอดขายรวมรายวัน Cockpit (สรุป)
</div>
<input type="hidden" name="month" id="month" value="<?php echo $DI_MONTH; ?>">
<input type="hidden" name="year" id="year" class="form-control" value="<?php echo $DI_YEAR; ?>">
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
                <th>สาขา</th>
                <th>จำนวนลูกค้า</th>
                <th>มูลค่ารวม</th>
                <th>ภาษี 7%</th>
                <th>มูลค่ารวมภาษี</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql_summary = "SELECT DI_DATE,BRANCH,SUM(TRD_B_SELL) AS SUM_TRD_B_SELL,SUM(TRD_B_VAT) AS SUM_TRD_B_VAT,SUM(TRD_G_KEYIN) AS SUM_TRD_G_KEYIN 
            FROM  ims_product_sale_cockpit 
            WHERE DI_MONTH = '" . $DI_MONTH . "' AND DI_YEAR = '" . $DI_YEAR . "' AND BRANCH = '" . $BRANCH . "' 
            GROUP BY DI_DATE,BRANCH 
            ORDER BY BRANCH,DI_DATE ";

            $statement_summary = $conn->query($sql_summary);
            $results_summary = $statement_summary->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results_summary

            as $row_summary) {

            $sql_count = "SELECT AR_CODE FROM ims_product_sale_cockpit 
                              WHERE  DI_DATE = '" . $row_summary['DI_DATE'] . "' and BRANCH = '" . $row_summary['BRANCH'] . "' GROUP BY AR_CODE";
            $statement_count = $conn->query($sql_count);
            $results_count = $statement_count->fetchAll(PDO::FETCH_ASSOC);
            $customer_count = 0;
            foreach ($results_count as $row_count) {
                $customer_count++;
            }
            ?>

            <tr>
                <td><?php echo htmlentities($row_summary['DI_DATE']); ?></td>
                <td><?php echo htmlentities($row_summary['BRANCH']); ?></td>
                <td align="right"><p
                            class="number"><?php echo htmlentities($customer_count); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($row_summary['SUM_TRD_B_SELL'], 2)); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($row_summary['SUM_TRD_B_VAT'], 2)); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(number_format($row_summary['SUM_TRD_G_KEYIN'], 2)); ?></p>
                </td>
                <?php } ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
include('includes/Modal-Logout.php');
include('includes/Footer.php');
?>

<!-- Scroll to top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/myadmin.min.js"></script>


</body>
</html>

