<?php

include("config/connect_db.php");

$month = $_POST["month"];
$year = $_POST["year"];
$product_group = $_POST["product_group"];

switch ($product_group) {
    case 'P1' :
        $product_group_name = "ยาง";
        break;
    case 'P2' :
        $product_group_name = "อะไหล่";
        break;
    case 'P3' :
        $product_group_name = "ค่าแรง-ค่าบริการ";
        break;
    case 'P4' :
        $product_group_name = "อื่นๆ";
        break;
    case 'PALL' :
        $product_group_name = "รวม ยาง + อะไหล่ + ค่าแรง-ค่าบริการ";
        break;
}

//$month = "4";
//$year = "2022";

$month_name = "";

$sql_month = " SELECT * FROM ims_month where month = '" . $month . "'";
$stmt_month = $conn->prepare($sql_month);
$stmt_month->execute();
$MonthRecords = $stmt_month->fetchAll();
foreach ($MonthRecords as $row) {
    $month_name = $row["month_name"];
}

//$myfile = fopen("param_post.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $month . "| month_name " . $month_name . "| branch = " . $_POST["branch"] . "| Branch Name = "
//    . $branch_name . " | " . $sql_month . " | " . $sql_branch);
//fclose($myfile);

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script src='js/util.js'></script>

    <title>สงวนออโต้คาร์</title>

    <script>
        function Chart_Page(branch) {
            PageAction = "";
            switch (branch) {
                case 'CP-340':
                    PageAction = 'cp_line_chart_year_branch';
                    break;
                default :
                    PageAction = "cp_line_chart_year_branch2";
                    break;
            }
            $('#branch').val(branch);
            document.forms['myform'].action = PageAction;
            document.forms['myform'].target = '_blank';
            document.forms['myform'].submit();
            return true;
        }
    </script>

</head>

<body onload="">

<p class="card">
<div class="card-header bg-primary text-white">
    <i class="fa fa-signal" aria-hidden="true"></i> ยอดขายเปรียบเทียบ เดือน/ปี
</div>
<input type="hidden" name="month" id="month" value="<?php echo $month; ?>">
<input type="hidden" name="year" id="year" class="form-control" value="<?php echo $year; ?>">
<div class="card-body">
    <a id="myLink" href="#" onclick="PrintPage();"><i class="fa fa-print"></i> พิมพ์</a>
</div>

<?php for ($loop = 1; $loop <= 4; $loop++) {

    switch ($loop) {
        case 1:
            $BRANCH = "CP-340";
            break;
        case 2:
            $BRANCH = "CP-BB";
            break;
        case 3:
            $BRANCH = "CP-BY";
            break;
        case 4:
            $BRANCH = "CP-RP";
            break;
    } ?>

    <div class="card">
        <div class="card-body">
            <form id="myform" name="myform" method="post">
                <input type="hidden" id="branch" name="branch">
                <input type="hidden" name="product_group" id="product_group" class="form-control"
                       value="<?php echo $product_group; ?>">
                <input type="hidden" name="product_group_name" id="product_group_name" class="form-control"
                       value="<?php echo $product_group_name; ?>">

                <div class="card-body">
                    <h4><span class="badge bg-success">จำนวนผู้ใช้บริการ สาขา : <?php echo $BRANCH ?></span></h4>
                    <!--a id="myChartLink" href="#" onclick="Chart_Page('<?php echo $BRANCH ?>');">
                        <button type="button" class="btn btn-outline-primary">ดู Graph</button>
                    </a-->
                </div>
            </form>
            <table id="example" class="display table table-striped table-bordered"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ปี</th>
                    <th>มกราคม</th>
                    <th>กุมภาพันธ์</th>
                    <th>มีนาคม</th>
                    <th>เมษายน</th>
                    <th>พฤษภาคม</th>
                    <th>มิถุนายน</th>
                    <th>กรกฎาคม</th>
                    <th>สิงหาคม</th>
                    <th>กันยายน</th>
                    <th>ตุลาคม</th>
                    <th>พฤศจิกายน</th>
                    <th>ธันวาคม</th>
                </tr>
                </tr>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                <?php
                $date = date("d/m/Y");
                $total = 0;
                if ($product_group === 'PALL') {
                    $where = " WHERE BRANCH = '" . $BRANCH . "'";
                }

                $sql_branch = " 
SELECT DI_YEAR,
SUM(IF(DI_MONTH='1',cust_count,0)) AS 1_QTY,
SUM(IF(DI_MONTH='2',cust_count,0)) AS 2_QTY,
SUM(IF(DI_MONTH='3',cust_count,0)) AS 3_QTY,
SUM(IF(DI_MONTH='4',cust_count,0)) AS 4_QTY,
SUM(IF(DI_MONTH='5',cust_count,0)) AS 5_QTY,
SUM(IF(DI_MONTH='6',cust_count,0)) AS 6_QTY,
SUM(IF(DI_MONTH='7',cust_count,0)) AS 7_QTY,
SUM(IF(DI_MONTH='8',cust_count,0)) AS 8_QTY,
SUM(IF(DI_MONTH='9',cust_count,0)) AS 9_QTY,
SUM(IF(DI_MONTH='10',cust_count,0)) AS 10_QTY,
SUM(IF(DI_MONTH='11',cust_count,0)) AS 11_QTY,
SUM(IF(DI_MONTH='12',cust_count,0)) AS 12_QTY
FROM v_customer_cockpit_count "
. $where
. " GROUP BY DI_YEAR 
ORDER BY DI_YEAR DESC ";

                /*
                                $my_file = fopen("str_qry.txt", "w") or die("Unable to open file!");
                                fwrite($my_file, "SQL " . " = " . " | " . $sql_branch);
                                fclose($my_file);
                */

                $statement_branch = $conn->query($sql_branch);
                $results_branch = $statement_branch->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results_branch

                as $row_branch) { ?>

                <tr>
                    <td><?php echo htmlentities($row_branch['DI_YEAR']); ?></td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['1_QTY'])) ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['2_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['3_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['4_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['5_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['6_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['7_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['8_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['9_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['10_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['11_QTY'])); ?></p>
                    </td>
                    <td align="right"><p
                                class="number"><?php echo htmlentities(($row_branch['12_QTY'])); ?></p>
                    </td>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

<?php } ?>


<div class="card">
    <div class="card-body">
        <h4><span class="badge bg-success">จำนวนผู้ใช้บริการ รวมทุกสาขา</span></h4>

        <!--?php include('cp_line_chart_4year.php'); ?-->

        <table id="example" class="display table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ปี</th>
                <th>มกราคม</th>
                <th>กุมภาพันธ์</th>
                <th>มีนาคม</th>
                <th>เมษายน</th>
                <th>พฤษภาคม</th>
                <th>มิถุนายน</th>
                <th>กรกฎาคม</th>
                <th>สิงหาคม</th>
                <th>กันยายน</th>
                <th>ตุลาคม</th>
                <th>พฤศจิกายน</th>
                <th>ธันวาคม</th>
            </tr>
            </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            <?php
            $date = date("d/m/Y");
           $total = 0;


            $sql_branch = "
SELECT 
DI_YEAR,
SUM(IF(DI_MONTH='1',cust_count,0)) AS 1_QTY,
SUM(IF(DI_MONTH='2',cust_count,0)) AS 2_QTY,
SUM(IF(DI_MONTH='3',cust_count,0)) AS 3_QTY,
SUM(IF(DI_MONTH='4',cust_count,0)) AS 4_QTY,
SUM(IF(DI_MONTH='5',cust_count,0)) AS 5_QTY,
SUM(IF(DI_MONTH='6',cust_count,0)) AS 6_QTY,
SUM(IF(DI_MONTH='7',cust_count,0)) AS 7_QTY,
SUM(IF(DI_MONTH='8',cust_count,0)) AS 8_QTY,
SUM(IF(DI_MONTH='9',cust_count,0)) AS 9_QTY,
SUM(IF(DI_MONTH='10',cust_count,0)) AS 10_QTY,
SUM(IF(DI_MONTH='11',cust_count,0)) AS 11_QTY,
SUM(IF(DI_MONTH='12',cust_count,0)) AS 12_QTY
FROM v_customer_cockpit_count
GROUP BY DI_YEAR 
ORDER BY DI_YEAR DESC ";

            $statement_branch = $conn->query($sql_branch);
            $results_branch = $statement_branch->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results_branch as $row_branch) { ?>

            <tr>
                <td align="right"><p class="number"><?php echo htmlentities($row_branch['DI_YEAR']); ?></p></td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['1_QTY'])) ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['2_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['3_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['4_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['5_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['6_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['7_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['8_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['9_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['10_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['11_QTY'])); ?></p>
                </td>
                <td align="right"><p
                            class="number"><?php echo htmlentities(($row_branch['12_QTY'])); ?></p>
                </td>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<div class="card-body">
    <a id="myLink" href="#" onclick="PrintPage();"><i class="fa fa-print"></i> พิมพ์</a>
</div>

<?php include("includes/stick_menu.php"); ?>

</body>
</html>

