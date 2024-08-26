<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/record_util.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM ims_sale_target WHERE id = " . $id;
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "sale_id" => $result['sale_id'],
            "target_month" => $result['target_month'],
            "target_year" => $result['target_year'],
            "target_money" => $result['target_money'],
            "status" => $result['status']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'ADD') {

    if ($_POST["target_month"] != '' && $_POST["target_year"]) {

        $sql_branch = "SELECT * FROM ims_branch WHERE chk_cond = 'Y' ORDER BY id ";

        $statement_branch = $conn->query($sql_branch);
        $results_branch = $statement_branch->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results_branch as $row_branch) {

            $target_month = $_POST["target_month"];
            $target_year = $_POST["target_year"];
            $target_money = $_POST["target_money"];
            $sale_id = $row_branch['branch'];

            $sql_find = "SELECT * FROM ims_sale_target WHERE sale_id =  '" . $sale_id . "' AND target_month = '" .  $target_month .  "'  AND target_year = '" . $target_year . "'";

            $nRows = $conn->query($sql_find)->fetchColumn();

            if ($nRows > 0) {
                $res = 2 ;
            } else {
                $sql = "INSERT INTO ims_sale_target(sale_id,target_month,target_year,target_month_order,target_year_order,target_money) 
                VALUES (:sale_id,:target_month,:target_year,:target_month_order,:target_year_order,:target_money)";

                $query = $conn->prepare($sql);
                $query->bindParam(':sale_id', $sale_id, PDO::PARAM_STR);
                $query->bindParam(':target_month', $target_month, PDO::PARAM_STR);
                $query->bindParam(':target_year', $target_year, PDO::PARAM_STR);
                $query->bindParam(':target_month_order', $target_month, PDO::PARAM_STR);
                $query->bindParam(':target_year_order', $target_year, PDO::PARAM_STR);
                $query->bindParam(':target_money', $target_money, PDO::PARAM_STR);

                $query->execute();
                $lastInsertId = $conn->lastInsertId();

                if ($lastInsertId) {
                    $res = 1 ;
                } else {
                    $res = 3 ;
                }
            }
        }
        echo $res;
    }

}


if ($_POST["action"] === 'UPDATE') {

    if ($_POST["target_month"] != '' && $_POST["target_year"] != '' && $_POST["target_money"] != '') {

        $id = $_POST["id"];
        $target_money = $_POST["target_money"];
        $sql_find = "SELECT * FROM ims_sale_target WHERE id = " . $id ;
/*
        $sql_target_s .= "\n\r" . $sql_find . "\n\r" . $_POST["target_month"]  . " | " . $_POST["target_year"] . " | " .  $target_money . " | " .  $status;
        $my_file = fopen("target_point.txt", "w") or die("Unable to open file!");
        fwrite($my_file, "sale_point = " . $sql_target_s);
        fclose($my_file);
*/

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE ims_sale_target SET target_money=:target_money
            WHERE id = :id";
/*
            $my_file = fopen("update_target_point.txt", "w") or die("Unable to open file!");
            fwrite($my_file, "update = " . $sql_update);
            fclose($my_file);
*/

            $query = $conn->prepare($sql_update);
            $query->bindParam(':target_money', $target_money, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM ims_sale_target WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM ims_sale_target WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_SALE_TARGET') {

    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (sale_id LIKE :sale_id or
        target_year LIKE :target_year ) ";
        $searchArray = array(
            'sale_id' => "%$searchValue%",
            'target_year' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ims_sale_target ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ims_sale_target WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];


    $columnName = " target_year_order desc , target_month_order desc , sale_id ";

## Fetch records
    $stmt = $conn->prepare("SELECT * FROM ims_sale_target WHERE 1 " . $searchQuery
        . " ORDER BY " . $columnName . " LIMIT :limit,:offset");

// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $data[] = array(
                "id" => $row['id'],
                "sale_id" => $row['sale_id'],
                "target_month" => $row['target_month'],
                "target_year" => $row['target_year'],
                "target_money" => $row['target_money'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "status" => $row['status'] === 'Active' ? "<div class='text-success'>" . $row['status'] . "</div>" : "<div class='text-muted'> " . $row['status'] . "</div>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "sale_id" => $row['sale_id'],
                "target_month" => $row['target_month'],
                "select" => "<button type='button' name='select' id='" . $row['sale_id'] . "@" . $row['target_month'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
</button>",
            );
        }

    }

## Response Return Value
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);


}
