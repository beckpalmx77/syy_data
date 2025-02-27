<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h3>Search Information</h3>
    <form id="searchForm" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" id="ADDB_SEARCH" name="ADDB_SEARCH" class="form-control" placeholder="Company Name">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
    <table id="resultsTable" class="table table-bordered">
        <thead>
        <tr>
            <th>Company</th>
            <th>Branch</th>
            <th>Address</th>
            <th>Phone</th>
            <th>SKU</th>
            <th>Product Name</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        let table = $('#resultsTable').DataTable();

        $('#searchForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                ADDB_SEARCH: $('#ADDB_SEARCH').val(),
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val()
            };

            $.ajax({
                url: 'model/get_data_history_customer.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    table.clear().draw();
                    data.forEach(row => {
                        table.row.add([
                            row.ADDB_COMPANY,
                            row.ADDB_BRANCH,
                            row.ADDB_ADDB_1,
                            row.ADDB_PHONE,
                            row.SKU_CODE,
                            row.SKU_NAME,
                            row.TRD_QTY,
                            row.TRD_U_PRC,
                            row.DI_DATE
                        ]).draw();
                    });
                }
            });
        });
    });
</script>
</body>
</html>

