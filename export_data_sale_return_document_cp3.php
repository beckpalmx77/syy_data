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

                                    <div class="modal-body">

                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="customer_name"
                                                       class="control-label">ค้นหาตามชื่อลูกค้า</label>
                                                <i class="fa fa-address-card"
                                                   aria-hidden="true"></i>
                                                <input type="text" class="form-control"
                                                       id="customer_name"
                                                       name="customer_name"
                                                       placeholder="">
                                            </div>

                                            <div class="col-sm-3">
                                                <label for="car_no"
                                                       class="control-label">ค้นหาตามทะเบียนรถยนต์</label>
                                                <i class="fa fa-car"
                                                   aria-hidden="true"></i>
                                                <input type="text" class="form-control"
                                                       id="car_no"
                                                       name="car_no"
                                                       placeholder="">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label>ช่วงเวลา</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="date_option" id="all_time" value="all" checked>
                                                    <label class="form-check-label" for="all_time">
                                                        ทุกช่วงเวลา
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="date_option" id="select_range" value="range">
                                                    <label class="form-check-label" for="select_range">
                                                        เลือกตามช่วงวันที่
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body">

                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="doc_date_start"
                                                       class="control-label">จากวันที่</label>
                                                <i class="fa fa-calendar"
                                                   aria-hidden="true"></i>
                                                <input type="text" class="form-control datepicker"
                                                       id="doc_date_start"
                                                       name="doc_date_start"
                                                       required="required"
                                                       readonly="true"
                                                       placeholder="">
                                            </div>

                                            <div class="col-sm-3">
                                                <label for="doc_date_to"
                                                       class="control-label">ถึงวันที่</label>
                                                <i class="fa fa-calendar"
                                                   aria-hidden="true"></i>
                                                <input type="text" class="form-control datepicker"
                                                       id="doc_date_to"
                                                       name="doc_date_to"
                                                       required="required"
                                                       readonly="true"
                                                       placeholder="">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <button type="submit" class="btn btn-success"
                                                id="btnExport"> Export <i
                                                    class="fa fa-check"></i>
                                        </button>

                                    </div>

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

<script>
    $(document).ready(function () {
        function formatDate(date) {
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function getDefaultDates() {
            let today = new Date();
            let firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            return {
                start: formatDate(firstDayOfMonth),
                end: formatDate(today)
            };
        }

        let defaultDates = getDefaultDates();

        // ตั้งค่าวันที่เริ่มต้น
        $('#doc_date_start').val(defaultDates.start);
        $('#doc_date_to').val(defaultDates.end);

        // เปิดใช้งาน datepicker
        $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            todayHighlight: true,
            language: "th",
            autoclose: true,
            todayBtn: true
        });

        function toggleDateInputs() {
            if ($("#all_time").is(":checked")) {
                $("#doc_date_start, #doc_date_to").prop("disabled", true).val("");
            } else {
                $("#doc_date_start, #doc_date_to").prop("disabled", false);
                // ตั้งค่ากลับไปเป็นวันที่ 1 ของเดือนปัจจุบันและวันที่ปัจจุบัน
                $('#doc_date_start').val(defaultDates.start);
                $('#doc_date_to').val(defaultDates.end);
            }
        }

        // เรียกใช้งานเมื่อโหลดหน้าเว็บ
        toggleDateInputs();

        // ตรวจจับการเปลี่ยนค่า radio button
        $("input[name='date_option']").change(function () {
            toggleDateInputs();
        });
    });
</script>

</body>
</html>
