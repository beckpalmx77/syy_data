<?php
include('includes/Header.php');
include('config/connect_sqlserver.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {

    ?>

    <!DOCTYPE html>
    <html lang="th">

    <style>

        .feedback {
            background-color: #31B0D5;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            border-color: #46b8da;
        }


        #menu_fix_button {
            position: fixed;
            bottom: 4px;
            right: 80px;
        }

    </style>

    <body id="page-top">
    <div id="wrapper">


        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><span id="title"></span></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo $_SESSION['dashboard_page'] ?>">Home</a>
                            </li>
                            <li class="breadcrumb-item"><span id="main_menu"></li>
                            <li class="breadcrumb-item active"
                                aria-current="page"><span id="sub_menu"></li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-12">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <section class="container-fluid">

                                        <form method="post" id="MainrecordForm">
                                            <input type="hidden" class="form-control" id="KeyAddData" name="KeyAddData"
                                                   value="">
                                            <div class="modal-body">
                                                <div class="modal-body">


                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                           class="display"
                                                           id="TableHistoryCustomerDetailList"
                                                           width="100%">
                                                        <thead>
                                                        <tr>
                                                            <th>ลำดับที่</th>
                                                            <th>เลขที่เอกสาร</th>
                                                            <th>วันที่</th>
                                                            <th>ชื่อลูกค้า</th>
                                                            <th>ทะเบียนรถ</th>
                                                            <th>ยี่ห้อรถ/รุ่น</th>
                                                            <th>เลขไมล์</th>
                                                            <th>รหัสสินค้า</th>
                                                            <th>ชื่อสินค้า</th>
                                                            <th>จำนวน</th>
                                                            <th>จำนวนเงิน</th>
                                                            <th>Detail</th>
                                                        </tr>
                                                        </thead>
                                                    </table>


                                                </div>
                                            </div>

                                            <?php include("includes/stick_menu.php"); ?>

                                            <div class="modal-footer">
                                                <input type="hidden" name="id" id="id"/>
                                                <input type="hidden" name="save_status" id="save_status"/>
                                                <input type="hidden" name="action" id="action"
                                                       value=""/>
                                                <button type="button" class="btn btn-danger"
                                                        id="btnClose">Close <i
                                                            class="fa fa-window-close"></i>
                                                </button>
                                            </div>
                                        </form>


                                    </section>


                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal fade" id="recordModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Modal title</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×
                                    </button>
                                </div>
                                <form method="post" id="recordForm">
                                    <div class="modal-body">
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="ADDB_COMPANY"
                                                       class="control-label">ชื่อลูกค้า</label>
                                                <input type="text" class="form-control"
                                                       id="ADDB_COMPANY"
                                                       name="ADDB_COMPANY"
                                                       required="required"
                                                       placeholder="">
                                            </div>


                                            <div class="form-group">
                                                <label for="ADDB_ADDB_1"
                                                       class="control-label">ที่อยู่ 1</label>
                                                <input type="text" class="form-control"
                                                       id="ADDB_ADDB_1"
                                                       name="ADDB_ADDB_1"
                                                       required="required"
                                                       placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <label for="ADDB_ADDB_2"
                                                       class="control-label">ที่อยู่ 2</label>
                                                <input type="text" class="form-control"
                                                       id="ADDB_ADDB_2"
                                                       name="ADDB_ADDB_2"
                                                       required="required"
                                                       placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <label for="ADDB_ADDB_3"
                                                       class="control-label">ที่อยู่ 3</label>
                                                <input type="text" class="form-control"
                                                       id="ADDB_ADDB_3"
                                                       name="ADDB_ADDB_3"
                                                       required="required"
                                                       placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <label for="ADDB_PROVINCE"
                                                       class="control-label">จังหวัด</label>
                                                <input type="text" class="form-control"
                                                       id="ADDB_PROVINCE"
                                                       name="ADDB_PROVINCE"
                                                       required="required"
                                                       placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <label for="ADDB_PHONE"
                                                       class="control-label">หมายเลขโทรศัพท์</label>
                                                <input type="text" class="form-control"
                                                       id="ADDB_PHONE"
                                                       name="ADDB_PHONE"
                                                       required="required"
                                                       placeholder="">
                                            </div>

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="id" id="id"/>
                                        <input type="hidden" name="action" id="action" value=""/>
                                        <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close <i
                                                    class="fa fa-window-close"></i>
                                        </button>
                                    </div>
                                </form>

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


    <!-- Bootstrap Touchspin -->
    <script src="vendor/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js"></script>
    <!-- ClockPicker -->

    <!-- RuangAdmin Javascript -->
    <script src="js/myadmin.min.js"></script>
    <script src="js/util.js"></script>
    <script src="js/Calculate.js"></script>
    <!-- Javascript for this page -->

    <script src="js/modal/show_supplier_modal.js"></script>
    <script src="js/modal/show_product_modal.js"></script>
    <script src="js/modal/show_unit_modal.js"></script>

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"/-->

    <script src="vendor/datatables/v11/bootbox.min.js"></script>
    <script src="vendor/datatables/v11/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="vendor/datatables/v11/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="vendor/datatables/v11/buttons.dataTables.min.css"/>

    <script src="vendor/date-picker-1.9/js/bootstrap-datepicker.js"></script>
    <script src="vendor/date-picker-1.9/locales/bootstrap-datepicker.th.min.js"></script>
    <!--link href="vendor/date-picker-1.9/css/date_picker_style.css" rel="stylesheet"/-->
    <link href="vendor/date-picker-1.9/css/bootstrap-datepicker.css" rel="stylesheet"/>

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
            $('#doc_date').datepicker({
                format: "dd-mm-yyyy",
                todayHighlight: true,
                language: "th",
                autoclose: true
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $(".icon-input-btn").each(function () {
                let btnFont = $(this).find(".btn").css("font-size");
                let btnColor = $(this).find(".btn").css("color");
                $(this).find(".fa").css({'font-size': btnFont, 'color': btnColor});
            });
        });
    </script>


    <script type="text/javascript">
        let queryString = new Array();
        $(function () {
            if (queryString.length == 0) {
                if (window.location.search.split('?').length > 1) {
                    let params = window.location.search.split('?')[1].split('&');
                    for (let i = 0; i < params.length; i++) {
                        let key = params[i].split('=')[0];
                        let value = decodeURIComponent(params[i].split('=')[1]);
                        queryString[key] = value;
                    }
                }
            }

            let data = "<b>" + queryString["title"] + "</b>";
            $("#title").html(data);
            $("#main_menu").html(queryString["main_menu"]);
            $("#sub_menu").html(queryString["sub_menu"]);
            $('#action').val(queryString["action"]);
            if (queryString["car_no"] != null && queryString["customer_name"] != null) {

                $('#car_no').val(queryString["car_no"]);
                $('#customer_name').val(queryString["customer_name"]);

                Load_Data_Detail(queryString["car_no"], queryString["customer_name"], queryString["sku_name"]);
            }
        });
    </script>

    <script>
        function Load_Data_Detail(car_no, customer_name, sku_name) {

            let formData = {
                action: "GET_HISTORY_DETAIL",
                sub_action: "GET_MASTER",
                car_no: car_no,
                customer_name: customer_name,
                sku_name: sku_name
            };
            let dataRecords = $('#TableHistoryCustomerDetailList').DataTable({
                "paging": false,
                "ordering": false,
                'info': false,
                "searching": false,
                'lengthMenu': [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
                'language': {
                    search: 'ค้นหา', lengthMenu: 'แสดง _MENU_ รายการ',
                    info: 'หน้าที่ _PAGE_ จาก _PAGES_',
                    infoEmpty: 'ไม่มีข้อมูล',
                    zeroRecords: "ไม่มีข้อมูลตามเงื่อนไข",
                    infoFiltered: '(กรองข้อมูลจากทั้งหมด _MAX_ รายการ)',
                    paginate: {
                        previous: 'ก่อนหน้า',
                        last: 'สุดท้าย',
                        next: 'ต่อไป'
                    }
                },
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'model/manage_customer_service_history_process.php',
                    'data': formData
                },
                'columns': [
                    {data: 'line_no'},
                    {data: 'DI_REF'},
                    {data: 'DI_DATE'},
                    {data: 'ADDB_COMPANY'},
                    {data: 'ADDB_BRANCH'},
                    {data: 'ADDB_ADDB'},
                    {data: 'KM'},
                    {data: 'SKU_CODE'},
                    {data: 'SKU_NAME'},
                    {data: 'TRD_QTY', className: "text-right"},
                    {data: 'TRD_B_AMT', className: "text-right"},
                    {data: 'detail'}
                ]
            });
        }
    </script>

    <script>

        $("#TableHistoryCustomerDetailList").on('click', '.detail', function () {
            let id = $(this).attr("id");

            let formData = {action: "GET_DATA", id: id};

            $.ajax({
                type: "POST",
                url: 'model/manage_customer_service_history_process.php',
                dataType: "json",
                data: formData,
                success: function (response) {
                    let len = response.length;
                    for (let i = 0; i < len; i++) {
                        let id = response[i].id;
                        let ADDB_COMPANY = response[i].ADDB_COMPANY;
                        let ADDB_ADDB_1 = response[i].ADDB_ADDB_1;
                        let ADDB_ADDB_2 = response[i].ADDB_ADDB_2;
                        let ADDB_ADDB_3 = response[i].ADDB_ADDB_3;
                        let ADDB_PROVINCE = response[i].ADDB_PROVINCE;
                        let ADDB_PHONE = response[i].ADDB_PHONE;

                        $('#recordModal').modal('show');
                        $('#id').val(id);
                        $('#ADDB_COMPANY').val(ADDB_COMPANY);
                        $('#ADDB_ADDB_1').val(ADDB_ADDB_1);
                        $('#ADDB_ADDB_2').val(ADDB_ADDB_2);
                        $('#ADDB_ADDB_3').val(ADDB_ADDB_3);
                        $('#ADDB_PROVINCE').val(ADDB_PROVINCE);
                        $('#ADDB_PHONE').val(ADDB_PHONE);
                        $('.modal-title').html("<i class='fa fa-plus'></i> Detail Record");
                        $('#action').val('UPDATE');
                        $('#save').val('Save');
                    }
                },
                error: function (response) {
                    alertify.error("error : " + response);
                }
            });
        });

    </script>


    <script>
        $(document).ready(function () {
            $("#btnClose").click(function () {
                window.close();
            });
        });
    </script>

    </body>

    </html>

<?php } ?>



