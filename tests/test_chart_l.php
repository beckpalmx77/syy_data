<?php

include("../config/connect_db.php");
$year = "2022";
include('../engine/get_data_chart_dash_year.php');

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script-->
<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->

<canvas id="myChart" style="width:100%;max-width:800px"></canvas>

<!--
<div class="col-md-3">
    <h4 style="color:rgb(93,36,248);"><?php echo $label1; ?></h4>
</div>
<div class="col-md-3">
    <h4 style="color:rgb(70,224,25);"><?php echo $label2; ?></h4>
</div>
<div class="col-md-3">
    <h4 style="color:rgb(236,109,24);"><?php echo $label3; ?></h4>
</div>
<div class="col-md-3">
    <h4 style="color:rgb(88,141,245);"><?php echo $label4; ?></h4>
</div>

-->


<script>

    const xValues = [
        'มกราคม',
        'กุมภาพันธ์',
        'มีนาคม',
        'เมษายน',
        'พฤษภาคม',
        'มิถุนายน',
        'กรกฎาคม',
        'สิงหาคม',
        'กันยายน',
        'ตุลาคม',
        'พฤศจิกายน',
        'ธันวาคม',
    ];

    new Chart("myChart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                label: 'CP-340',
                data: <?php echo $data1?>,
                borderColor: 'rgb(93,36,248)',
                fill: false
            }, {
                label: 'CP-BY',
                data: <?php echo $data2?>,
                borderColor: 'rgb(70,224,25)',
                fill: false
            }, {
                label: 'CP-BB',
                data: <?php echo $data3?>,
                borderColor: 'rgb(236,109,24)',
                fill: false
            }, {
                label: 'CP-RP',
                data: <?php echo $data4?>,
                borderColor: 'rgb(88,141,245)',
                fill: false
            }]
        },
        options: {
            legend: {
                display: true,
                labels: {
                    color: 'rgb(255, 99, 132)'
                }
            }
        }
    });
</script>