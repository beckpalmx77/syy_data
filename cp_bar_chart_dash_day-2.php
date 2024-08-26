<!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script-->


<canvas id="myChartBar2" style="width:100%;max-width:800px"></canvas>

<?php

include("engine/get_data_chart_dash_day2.php");

?>

<script>

    const ctx = document.getElementById('myChartBar2');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels_2?>,
            datasets: [{
                label: "CP-340",
                backgroundColor: "#6837ee",
                data: <?php echo $data2_1?>
            }, {
                label: "CP-BY",
                backgroundColor: "#24a326",
                data: <?php echo $data2_2?>
            }, {
                label: "CP-BB",
                backgroundColor: "#c7522c",
                data: <?php echo $data2_3?>
            }, {
                label: "CP-RP",
                backgroundColor: "#3bc7e0",
                data: <?php echo $data2_4?>
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



