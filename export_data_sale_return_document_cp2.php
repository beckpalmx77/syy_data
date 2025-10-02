<?php
include('includes/Header.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
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
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
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

                                                        <form id="from_data" method="post"
                                                              action="export_process/export_data_sale_return_document_cp2.php"
                                                              enctype="multipart/form-data">

                                                            <div class="modal-body">

                                                                <div class="modal-body">
                                                                    <div class="form-group row">

                                                                        <div class="col-sm-3">
                                                                            <label for="doc_date_start"
                                                                                   class="control-label">จากวันที่</label>
                                                                            <i class="fa fa-calendar"
                                                                               aria-hidden="true"></i>
                                                                            <input type="text" class="form-control"
                                                                                   id="doc_date_start"
                                                                                   name="doc_date_start"
                                                                                   required="required"
                                                                                   readonly="true"
                                                                                   placeholder="จากวันที่">
                                                                        </div>

                                                                        <div class="col-sm-3">
                                                                            <label for="doc_date_to"
                                                                                   class="control-label">ถึงวันที่</label>
                                                                            <i class="fa fa-calendar"
                                                                               aria-hidden="true"></i>
                                                                            <input type="text" class="form-control"
                                                                                   id="doc_date_to"
                                                                                   name="doc_date_to"
                                                                                   required="required"
                                                                                   readonly="true"
                                                                                   placeholder="ถึงวันที่">
                                                                        </div>


                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="product_cat" class="control-label">ประเภทสินค้า</label>
                                                                        <select id="product_cat" name="product_cat"
                                                                                class="form-control select2">
                                                                            <option value="-">-- เลือกทุกประเภท
                                                                                --
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="branch"
                                                                               class="control-label">สาขา</label>
                                                                        <select id="branch" name="branch"
                                                                                class="form-control"
                                                                                data-live-search="true"
                                                                                title="Please select">
                                                                            <option>CP-340</option>
                                                                            <option>CP-BY</option>
                                                                            <option>CP-RP</option>
                                                                            <option>CP-BB</option>
                                                                            <option>BTC</option>
                                                                            <option>ALL</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <input type="hidden" name="id" id="id"/>
                                                                <input type="hidden" name="save_status"
                                                                       id="save_status"/>
                                                                <input type="hidden" name="action" id="action"
                                                                       value=""/>
                                                                <button type="submit" class="btn btn-success"
                                                                        id="btnExport"> Export <i
                                                                            class="fa fa-check"></i>
                                                                </button>
                                                                <!--button type="button" class="btn btn-danger"
                                                                        id="btnClose">Close <i
                                                                            class="fa fa-window-close"></i>
                                                                </button-->
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap Datepicker -->
    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap Touchspin -->
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <!-- ClockPicker -->
    <script src="vendor/clock-picker/clockpicker.js"></script>
    <!-- RuangAdmin Javascript -->
    <script src="js/myadmin.min.js"></script>
    <!-- Javascript for this page -->

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

    <script src="js/MyFrameWork/framework_util.js"></script>
    <script src="js/util.js"></script>

    <script>
        $(document).ready(function () {
            let today = new Date();
            let doc_date = getDay2Digits(today) + "-" + getMonth2Digits(today) + "-" + today.getFullYear();
            $('#doc_date_start').val(doc_date);
            $('#doc_date_to').val(doc_date);
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#doc_date_start').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#doc_date_to').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

    <!--script>
        $(document).ready(function () {
            $('#product_cat').select2({
                placeholder: "-- กรุณาเลือกประเภทสินค้า --",
                allowClear: true,
                ajax: {
                    url: 'model/get_product_categories.php', // ไฟล์ PHP สำหรับดึงข้อมูล
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {search: params.term};
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {id: item.ICCAT_CODE, text: item.ICCAT_NAME};
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script-->

    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('#product_cat').select2({
                placeholder: "-- เลือกทุกประเภท --",
                allowClear: true,
                ajax: {
                    url: 'model/get_product_categories.php', // ไฟล์ PHP สำหรับดึงข้อมูล
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term // ส่งคำค้นหาไปยัง server
                        };
                    },
                    processResults: function (data) {
                        let options = [
                            {
                                id: "-",
                                text: "-- เลือกทุกประเภท --"
                            }
                        ]; // เพิ่มตัวเลือก "-- กรุณาเลือกประเภทสินค้า --" เป็นค่าแรก
                        return {
                            results: options.concat(
                                $.map(data, function (item) {
                                    return {
                                        id: item.ICCAT_CODE, // ค่าที่ส่งกลับ
                                        text: item.ICCAT_NAME // ข้อความที่จะแสดง
                                    };
                                })
                            )
                        };
                    },
                    cache: true
                }
            });

            // Reset to default option when user clears the selection
            $('#product_cat').on('select2:clear', function () {
                $('#product_cat').val('-').trigger('change');
            });
        });
    </script>


    </body>

    </html>

<?php } ?>