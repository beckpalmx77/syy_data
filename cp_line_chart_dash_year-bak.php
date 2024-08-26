<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>

<?php

//include("config/connect_db.php");

include ('engine/get_data_chart_dash_year.php');

//echo $data1;

?>

<script>

    const labels = [
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

    const data = {
        labels: labels,
        datasets: [{
            label: <?php echo $label1?>,
            backgroundColor: 'rgb(196,7,46)',
            borderColor: 'rgb(246,44,86)',
            data: <?php echo $data1?>,
            },
            {
                label: <?php echo $label2?>,
                backgroundColor: 'rgb(5,173,7)',
                borderColor: 'rgb(70,224,25)',
                data: <?php echo $data2?>,
            },
            {
                label: <?php echo $label3?>,
                backgroundColor: 'rgb(208,94,6)',
                borderColor: 'rgb(236,109,24)',
                data: <?php echo $data3?>,
            },
            {
                label: <?php echo $label4?>,
                backgroundColor: 'rgb(6,107,215)',
                borderColor: 'rgb(88,141,245)',
                data: <?php echo $data4?>,
            }
        ]
    };

    const config = {
        type: 'line',
        data: data,
        options: {}
    };

</script>

<div>
    <canvas id="myChart"></canvas>
</div>

<script>
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>