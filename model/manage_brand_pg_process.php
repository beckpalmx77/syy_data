<?php
session_start();
error_reporting(0);

include('../config/connect_pg_db.php');
include('../config/lang.php');
include('../util/record_util.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM sac_brands WHERE id = " . $id;
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "id" => $result['id'],
            "name" => $result['name'],
            "enable" => $result['enable']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["name"] !== '') {

        $name = $_POST["name"];
        $sql_find = "SELECT * FROM sac_brands WHERE name = '" . $name . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

if ($_POST["action"] === 'ADD') {
    if ($_POST["name"] !== '') {
        $id = "B-" . sprintf('%04s', LAST_ID($conn, "sac_brands", 'id'));
        $name = $_POST["name"];
        $enable = $_POST["enable"];
        $sql_find = "SELECT * FROM sac_brands WHERE name = '" . $name . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo $dup;
        } else {
            $sql = "INSERT INTO sac_brands(id,name,enable) VALUES (:id,:name,:enable)";
            $query = $conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':enable', $enable, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();

            if ($lastInsertId) {
                echo $save_success;
            } else {
                echo $error;
            }
        }
    }
}


if ($_POST["action"] === 'UPDATE') {

    if ($_POST["name"] != '') {

        $id = $_POST["id"];
        $id = $_POST["id"];
        $name = $_POST["name"];
        $enable = $_POST["enable"];
        $sql_find = "SELECT * FROM sac_brands WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE sac_brands SET id=:id,name=:name,enable=:enable            
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':enable', $enable, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM sac_brands WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM sac_brands WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}

if ($_POST["action"] === 'GET_BRAND') {

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
        $searchQuery = " AND (id LIKE :id or
        name LIKE :name ) ";
        $searchArray = array(
            'id' => "%$searchValue%",
            'name' => "%$searchValue%",
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM sac_brands ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM sac_brands WHERE  id is not null " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];


    $query_str = "SELECT * FROM sac_brands WHERE id <> 0 " . $searchQuery
        . " ORDER BY " . $columnName . " " . $columnSortOrder . " OFFSET :offset limit :limit";
    //. " ORDER BY " . $columnName . " " . $columnSortOrder . " ";

## Fetch records
    $stmt = $conn->prepare($query_str);

    /*
        $myfile = fopen("sql_sac_pg_conn.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $stmt);
        fclose($myfile);



        $myfile = fopen("sql_sac_pg_data.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $query_str);
        //fwrite($myfile, $query_str . " | limit =  " . (int)$row . " offset = " . (int)$rowperpage);
        fclose($myfile);

    */


// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':offset', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    foreach ($empRecords as $row) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $data[] = array(
                "id" => $row['id'],
                "id" => $row['id'],
                "name" => $row['name'],
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "delete" => "<button type='button' name='delete' id='" . $row['id'] . "' class='btn btn-danger btn-xs delete' data-toggle='tooltip' title='Delete'>Delete</button>",
                "enable" => $row['enable'] === 't' ? "<div class='text-success'>" . $row['enable'] . "</div>" : "<div class='text-muted'> " . $row['enable'] . "</div>"
            );
        } else {
            $data[] = array(
                "id" => $row['id'],
                "id" => $row['id'],
                "name" => $row['name'],
                "select" => "<button type='button' name='select' id='" . $row['id'] . "@" . $row['name'] . "' class='btn btn-outline-success btn-xs select' data-toggle='tooltip' title='select'>select <i class='fa fa-check' aria-hidden='true'></i>
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
