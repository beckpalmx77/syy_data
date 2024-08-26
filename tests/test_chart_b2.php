<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div>
    <div class="chart-container" style="position: relative; width:80vw">
        <canvas id="myChart"></canvas>
    </div>
</div>

<?php

    include("../config/connect_db.php");
    include("../engine/get_data_chart_dash_day.php");

?>

<script>

    //alert("OK" + <?php echo $labels?>);

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels?>,
            datasets: [{
                label: "CP-340",
                fillColor: "#4dc258",
                data: <?php echo $data1?>
            }, {
                label: "CP-BY",
                fillColor: "#8830ee",
                data: <?php echo $data2?>
            }, {
                label: "CP-BB",
                fillColor: "#FC9775",
                data: <?php echo $data3?>
            }, {
                label: "CP-RP",
                fillColor: "#5A69A6",
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



