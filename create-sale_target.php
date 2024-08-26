<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    $year = date("Y");
    $year_next =  date("Y") + 1 ;
    ?>

    <!DOCTYPE html>
    <html lang="th">

    <body id="page-top">
    <div id="wrapper">
        <?php
        include('includes/Side-Bar.php');
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php
                include('includes/Top-Bar.php');
                ?>

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?php echo urldecode($_GET['s']) ?></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page']?>">Home</a></li>
                            <li class="breadcrumb-item"><?php echo urldecode($_GET['m']) ?></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><?php echo urldecode($_GET['s']) ?></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <section class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12 col-md-offset-2">
                                                <div class="panel">
                                                    <div class="panel-body">

                                                        <form id="from_data">

                                                            <div class="form-group has-success">
                                                                <label class="control-label" for="select-testing">เลือกเดือน</label>

                                                                <div class=”form-group”>
                                                                    <select id="target_month" name="target_month"
                                                                            class="form-control" data-live-search="true"
                                                                            title="Please select">
                                                                        <?php
                                                                        for ($month_x = 1; $month_x <= 12; $month_x++) {
                                                                            echo "<option>" . $month_x .  "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>

                                                                </div>
                                                                <span class="help-block"></span>
                                                            </div>

                                                            <div class="form-group has-success">
                                                                <label class="control-label" for="select-testing">เลือกปี</label>
                                                                <div class=”form-group”>
                                                                    <select id="target_year" name="target_year"
                                                                            class="form-control" data-live-search="true"
                                                                            title="Please select">
                                                                        <?php
                                                                        for ($year_x = $year; $year_x <= $year_next; $year_x++) {
                                                                            echo "<option>" . $year_x .  "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>

                                                                </div>
                                                                <span class="help-block"></span>
                                                            </div>

                                                            <div class="form-group has-success">
                                                                <label for="target_money" class="control-label">ยอดเป้าหมาย</label>
                                                                <div class="">
                                                                    <input type="text" name="target_money"
                                                                           id="target_money"
                                                                           value="1500000"
                                                                           class="form-control"
                                                                           required="required">
                                                                </div>
                                                            </div>

                                                            <div class="form-group has-success">

                                                                <div class="">
                                                                    <button type="submit"
                                                                            class="btn btn-primary btn-block">
                                                                        Save
                                                                </div>
                                                            </div>

                                                            <div><input id="action" name="action" type="hidden"
                                                                        value="ADD">
                                                            </div>

                                                        </form>

                                                        <div id="result"></div>


                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col-md-8 col-md-offset-2 -->
                                        </div>
                                        <!-- /.row -->

                                    </section>


                                </div>

                            </div>

                        </div>

                    </div>
                    <!--Row-->

                    <!-- Row -->

                </div>

                <!---Container Fluid-->

            </div>

            <?php
            include('includes/Modal-Logout.php');
            include('includes/Footer.php');
            ?>

        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Select2 -->
    <script src="vendor/select2/dist/js/select2.min.js"></script>
    <!-- Bootstrap Datepicker -->
    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap Touchspin -->
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <!-- ClockPicker -->
    <script src="vendor/clock-picker/clockpicker.js"></script>
    <!-- RuangAdmin Javascript -->
    <script src="js/myadmin.min.js"></script>
    <!-- Javascript for this page -->

    <script src="js/MyFrameWork/framework_util.js"></script>

    <script>
        $(document).ready(function () {
            $("form").on("submit", function (event) {
                event.preventDefault();
                let formValues = $(this).serialize();
                $.post("model/manage_sale_target.php", formValues, function (response) {
                    if (response == 1) {
                        document.getElementById("from_data").reset();
                        alertify.success("บันทึกข้อมูลเรียบร้อย Save Data Success");

                    } else if (response == 2) {
                        alertify.error("ไม่สามารถบันทึกข้อมูลได้ มีรายการนี้แล้ว " + response);
                    } else {
                        alertify.error("ไม่สามารถบันทึกข้อมูลได้ DB Error " + response );
                    }
                });
            });
        });
    </script>



    </body>

    </html>

<?php } ?>