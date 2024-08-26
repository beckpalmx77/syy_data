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
                                                              action="export_process/export_process_data_history_customer_ext.php"
                                                              enctype="multipart/form-data">

                                                            <div class="modal-body">

                                                                <!--div class="modal-body">
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
                                                                </div-->

                                                                <div class="modal-body">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="ICCAT_CODE">เลือกประเภทบริการ :</label>
                                                                            <input type="hidden" name="ICCAT_CODE" id="ICCAT_CODE"
                                                                                   class="form-control">
                                                                            <input type="hidden" name="ICCAT_NAME" id="ICCAT_NAME"
                                                                                   class="form-control">
                                                                            <select id='selService' class='form-control'
                                                                                    onchange="Onchange_SERVICE_CODE();">
                                                                                <option value='0'>- ค้นหาประเภทบริการ -
                                                                                </option>
                                                                            </select>

                                                                        </div>

                                                                        <div class="col-sm-12">
                                                                            <label for="BRAND_CODE">เลือกยี่ห้อสินค้า :</label>
                                                                            <input type="hidden" name="BRAND_CODE" id="BRAND_CODE"
                                                                                   class="form-control">
                                                                            <input type="hidden" name="BRAND_NAME" id="BRAND_NAME"
                                                                                   class="form-control">
                                                                            <select id='selBrand' class='form-control'
                                                                                    onchange="Onchange_BRAND_CODE();">
                                                                                <option value='0'>- ค้นหายี่ห้อ -
                                                                                </option>
                                                                            </select>

                                                                        </div>

                                                                        <div class="col-sm-12">
                                                                            <label for="BRANCH">เลือกสาขา :</label>
                                                                            <input type="hidden" name="BRANCH" id="BRANCH"
                                                                                   class="form-control">
                                                                            <input type="hidden" name="BRANCH_NAME" id="BRANCH_NAME"
                                                                                   class="form-control">
                                                                            <select id='selBranch' class='form-control'
                                                                                    onchange="Onchange_BRANCH_CODE();">
                                                                                <option value='0'>- ค้นหาสาขา -
                                                                                </option>
                                                                            </select>

                                                                        </div>


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

    <script src="vendor/select2/dist/js/select2.min.js"></script>

    <!-- select2 css -->
    <link href='js/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

    <!-- select2 script -->
    <script src='js/select2/dist/js/select2.min.js'></script>

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

            $("#selService").select2({
                ajax: {
                    url: "model/get_service_ajax.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });

    </script>

    <script>
        $(document).ready(function () {

            $("#selBrand").select2({
                ajax: {
                    url: "model/get_brand_ajax.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });

    </script>

    <script>
        $(document).ready(function () {

            $("#selBranch").select2({
                ajax: {
                    url: "model/get_branch_ajax.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });

    </script>


    <script>

        function Onchange_SERVICE_CODE() {

            $('#ICCAT_NAME').val($("#selService").val());

        }
    </script>


    <script>

        function Onchange_BRAND_CODE() {

            $('#BRAND_NAME').val($("#selBrand").val());

        }
    </script>

    <script>

        function Onchange_BRANCH_CODE() {

            $('#BRANCH_NAME').val($("#selBranch").val());

        }
    </script>




    </body>

    </html>

<?php } ?>