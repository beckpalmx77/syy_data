<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    include("config/connect_db.php");
    require_once 'vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
    $detect = new Mobile_Detect;

    $year = $_POST["year"];

    ?>

    <!DOCTYPE html>
    <html lang="th">
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->
    <script
            src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <style>
        p.number {
            text-align-last: right;
        }
    </style>

    <body id="page-top" onload="showGraph_Cockpit_Year();showGraph_Tires_Brand();">
    <div id="wrapper">
        <?php
        include('includes/Side-Bar.php');
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                include('includes/Top-Bar.php');
                ?>
                <div class="container-fluid" id="container-wrapper">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    สถิติ ยอดขาย Cockpit แต่ละสาขา ปี
                                    <?php echo $year; ?>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="year" id="year" value="<?php echo $year; ?>">
                                    <h5 class="card-title">ปี <?php echo $year; ?></h5>
                                    <canvas id="myChartYear" width="200" height="200"></canvas>
                                </div>
                                <div class="card-body">
                                    <table id="example" class="display table table-striped table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>สาขา</th>
                                            <th>ยอดขาย</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>สาขา</th>
                                            <th>ยอดขาย</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php

                                        $total = 0;
                                        $sql_daily = "SELECT BRANCH,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as  TRD_G_KEYIN
                                                      FROM ims_product_sale_cockpit 
                                                      WHERE DI_YEAR = '" . $year . "'                                                      
                                                      GROUP BY  BRANCH , DI_YEAR
                                                      ORDER BY BRANCH";

                                        $statement_daily = $conn->query($sql_daily);
                                        $results_daily = $statement_daily->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($results_daily

                                        as $row_daily) { ?>

                                        <tr>
                                            <td><?php echo htmlentities($row_daily['BRANCH']); ?></td>
                                            <td>
                                                <p class="number"><?php echo htmlentities(number_format($row_daily['TRD_G_KEYIN'], 2)); ?></p>


                                                <?php $total = $total + $row_daily['TRD_G_KEYIN']; ?>
                                                <?php } ?>


                                        </tbody>
                                        <?php echo "ยอดขายรวมทุกสาขา " . number_format($total, 2) . " บาท " ?>
                                    </table>
                                </div>


                                <div id="content-wrapper" class="d-flex flex-column">
                                    <div id="content">
                                        <div>
                                            <div class="card-header">
                                                สถิติ ยอดขาย Cockpit แต่ละสาขา ปี
                                                <?php echo $year; ?>
                                            </div>
                                            <div class="card-body">

                                                <?php include('cp_line_chart_dash_year.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <?php include('display_chart_tires_brand_admin_year.php'); ?>

                    </div>

                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <!--script src="js/chart.js"></script-->

    <link href='vendor/calendar/main.css' rel='stylesheet'/>
    <script src='vendor/calendar/main.js'></script>
    <script src='vendor/calendar/locales/th.js'></script>

    <script>

        function showGraph_Tires_Brand() {
            {
                let year = $("#year").val();

                let barColors = [
                    "#0a4dd3",
                    "#17c024",
                    "#f3661a",
                    "#f81b61",
                    "#0c3f10",
                    "#1da5f2",
                    "#0e0b71",
                    "#e9e207",
                    "#07e9d8",
                    "#b91d47",
                    "#af43f5",
                    "#00aba9",
                    "#fcae13",
                    "#1d7804",
                    "#1a8cec",
                    "#50e310",
                    "#fa6ae4"
                ];

                $.post("engine/chart_data_pie_tires_brand_year.php", {year: year, branch: "2"}, function (data) {
                    console.log(data);
                    let label = [];
                    let label_name = [];
                    let total = [];
                    for (let i in data) {
                        label.push(data[i].BRN_CODE);
                        label_name.push(data[i].BRN_NAME);
                        total.push(parseFloat(data[i].TRD_G_KEYIN).toFixed(2));
                        //alert(label);
                    }

                    new Chart("myChart2", {
                        type: "doughnut",
                        data: {
                            labels: label_name,
                            datasets: [{
                                backgroundColor: barColors,
                                data: total
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: "-"
                            }
                        }
                    });

                })


            }
        }

    </script>

    <script>
        function showGraph_Cockpit_Year() {
            {

                let year = $("#year").val();

                let backgroundColor = '#7058f8';
                let borderColor = '#46d5f1';

                let hoverBackgroundColor = '#4a36a5';
                let hoverBorderColor = '#a2a1a3';

                $.post("engine/chart_data_cockpit_year.php", {year: year}, function (data) {
                    console.log(data);
                    let branch = [];
                    let total = [];
                    for (let i in data) {
                        branch.push(data[i].BRANCH);
                        total.push(parseFloat(data[i].TRD_G_KEYIN).toFixed(2));
                    }

                    let chartdata = {
                        labels: branch,
                        datasets: [{
                            label: 'ยอดขายรายรวม',
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
                            hoverBackgroundColor: hoverBackgroundColor,
                            hoverBorderColor: hoverBorderColor,
                            data: total
                        }]
                    };
                    let graphTarget = $('#myChartYear');
                    let barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata
                    })
                })
            }
        }

    </script>


    </body>

    </html>

<?php } ?>

