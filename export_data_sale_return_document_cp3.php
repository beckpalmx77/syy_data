<?php
include('includes/Header.php');

// ตรวจสอบว่า session มีการล็อกอินหรือไม่
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index");
    exit();
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบค้นหา</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="vendor/date-picker-1.9/css/bootstrap-datepicker.css">
</head>
<body id="page-top">
<div id="wrapper">

    <!-- Sidebar -->
    <?php include('includes/Side-Bar.php'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Top Bar -->
            <?php include('includes/Top-Bar.php'); ?>

            <!-- Main Container -->
            <div class="container-fluid" id="container-wrapper">

                <!-- Breadcrumb -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><?php echo urldecode($_GET['s']); ?></h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page']; ?>">Home</a></li>
                        <li class="breadcrumb-item"><?php echo urldecode($_GET['m']); ?></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo urldecode($_GET['s']); ?></li>
                    </ol>
                </div>

                <!-- Search Form -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="from_data" method="post"
                                      action="export_process/export_data_sale_return_document_cp3.php"
                                      enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="customer_name">ค้นหาตามชื่อลูกค้า</label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="car_no">ค้นหาตามทะเบียนรถยนต์</label>
                                        <input type="text" name="car_no" id="car_no" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-success"
                                            id="btnExport"> Export <i
                                                class="fa fa-check"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Main Container -->

            <!-- Footer -->
            <?php include('includes/Footer.php'); ?>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/select2/dist/js/select2.min.js"></script>
<script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
<!--script>
    $(document).ready(function () {
        $("#BtnExport").click(function () {
            const customer_name = $("#customer_name").val();
            const car_no = $("#car_no").val();
            $.ajax({
                url: "export_process/export_data_sale_return_document_cp3.php",
                type: "POST",
                data: { customer_name: customer_name, car_no: car_no },
                beforeSend: function () {
                    $("#BtnExport").text("Exporting...").prop("disabled", true);
                },
                success: function (response) {
                    alert("Export Success: " + response);
                },
                error: function (xhr, status, error) {
                    alert("Error: " + error);
                },
                complete: function () {
                    $("#BtnExport").text("Export").prop("disabled", false);
                }
            });
        });
    });
</script-->
</body>
</html>
