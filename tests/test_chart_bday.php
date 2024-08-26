<script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

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

    alert("OK");

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $labels ?>,
            datasets: [{
                label: <?php echo $label1 ?>,
                fillColor: "#5cac39",
                data: <?php echo $data1 ?>
            }, {
                label: <?php echo $label2 ?>,
                fillColor: "#9975fc",
                data: <?php echo $data2 ?>
            }, {
                label: <?php echo $label3 ?>,
                fillColor: "#FC9775",
                data: <?php echo $data3 ?>
            }, {
                label: <?php echo $label4 ?>,
                fillColor: "#5A69A6",
                data: <?php echo $data4 ?>
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



