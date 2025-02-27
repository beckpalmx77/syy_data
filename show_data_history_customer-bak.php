<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta date="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <script src="js/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome/css/font-awesome.css">
</head>
<body>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-12">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            </div>
            <div class="card-body">
                <section class="container-fluid">

                    <div class="col-md-12 col-md-offset-2">
                        <table id='TableRecordList' class='display dataTable'>
                            <thead>
                            <tr>
                                <th>รหัสเมนูหลัก</th>
                                <th>ชื่อเมนูหลัก</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Privilege</th>
                                <th>Action</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>รหัสเมนูหลัก</th>
                                <th>ชื่อเมนูหลัก</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Privilege</th>
                                <th>Action</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>

                        <div id="result"></div>

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php


?>


<!-- Scroll to top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/myadmin.min.js"></script>


<script src="vendor/datatables/v11/bootbox.min.js"></script>
<script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>

<style>

    .icon-input-btn {
        display: inline-block;
        position: relative;
    }

    .icon-input-btn input[type="submit"] {
        padding-left: 2em;
    }

    .icon-input-btn .fa {
        display: inline-block;
        position: absolute;
        left: 0.65em;
        top: 30%;
    }
</style>
<script>
    $(document).ready(function () {
        $(".icon-input-btn").each(function () {
            let btnFont = $(this).find(".btn").css("font-size");
            let btnColor = $(this).find(".btn").css("color");
            $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
        });
    });
</script>


</body>
</html>