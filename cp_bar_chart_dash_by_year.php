<?php

//include("config/connect_db.php");
include('engine/get_data_chart_dash_year.php');

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script-->
<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->

<canvas id="myChart" style="width:100%;max-width:800px"></canvas>

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
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                label: 'CP-340',
                data: <?php echo $data1?>,
                backgroundColor: "#6837ee",
                fill: false
            }, {
                label: 'CP-BY',
                data: <?php echo $data2?>,
                backgroundColor: "#24a326",
                fill: false
            }, {
                label: 'CP-BB',
                data: <?php echo $data3?>,
                backgroundColor: "#c7522c",
                fill: false
            }, {
                label: 'CP-RP',
                data: <?php echo $data4?>,
                backgroundColor: "#3bc7e0",
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