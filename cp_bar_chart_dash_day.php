<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->



<!--canvas id="myChartBar"></canvas-->

<canvas id="myChartBar" style="width:100%;max-width:800px"></canvas>

<?php

include("engine/get_data_chart_dash_day.php");

?>

<script>

    //alert("OK" + <?php echo $labels?>);

    const ctx = document.getElementById('myChartBar');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels?>,
            datasets: [{
                label: "CP-340",
                backgroundColor: "#6837ee",
                data: <?php echo $data1?>
            }, {
                label: "CP-BY",
                backgroundColor: "#24a326",
                data: <?php echo $data2?>
            }, {
                label: "CP-BB",
                backgroundColor: "#c7522c",
                data: <?php echo $data3?>
            }, {
                label: "CP-RP",
                backgroundColor: "#3bc7e0",
                data: <?php echo $data4?>
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>



