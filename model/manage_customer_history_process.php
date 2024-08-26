<?php
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
include('../config/connect_db.php');
include("../config/connect_sqlserver.php");
include('../config/lang.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();
    $sql_get = " SELECT * FROM ADDRBOOK WHERE ADDB_COMPANY LIKE '%" . $id . "%' AND ADDB_PHONE IS NOT NULL LIMIT 1";
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    //$myfile = fopen("qry_file.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $sql_get);
    //fclose($myfile);

    $nRows = $conn->query($sql_get)->fetchColumn();
    if ($nRows > 0) {
        foreach ($results as $result) {
            $return_arr[] = array("id" => $result['id'],
                "ADDB_COMPANY" => $result['ADDB_COMPANY'],
                "ADDB_ADDB_1" => $result['ADDB_ADDB_1'],
                "ADDB_ADDB_2" => $result['ADDB_ADDB_2'],
                "ADDB_ADDB_3" => $result['ADDB_ADDB_3'],
                "ADDB_PROVINCE" => $result['ADDB_PROVINCE'],
                "ADDB_PHONE" => $result['ADDB_PHONE']);
        }
    } else {
        $return_arr[] = array("id" => $result['id'],
            "ADDB_COMPANY" => "",
            "ADDB_ADDB_1" => "",
            "ADDB_ADDB_2" => "",
            "ADDB_ADDB_3" => "",
            "ADDB_PROVINCE" => "",
            "ADDB_PHONE" => "");
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'GET_HISTORY_DETAIL') {

    ## Read value
    $car_no = $_POST['car_no'];
    $customer_name = $_POST['customer_name'];
    $sku_name = $_POST['sku_name'];

    $addb_phone = "";

    $sql_data_select = " SELECT 
TRANSTKD.TRD_KEY , 
ADDRBOOK.ADDB_KEY , 
ADDRBOOK.ADDB_BRANCH , 
ADDRBOOK.ADDB_SEARCH ,
ADDRBOOK.ADDB_ADDB_1 , 
ADDRBOOK.ADDB_ADDB_2 ,
ADDRBOOK.ADDB_ADDB_3 ,  
ADDRBOOK.ADDB_COMPANY ,
ADDRBOOK.ADDB_PHONE ,
DOCINFO.DI_REF , 
DOCINFO.DI_DATE,
DAY(DI_DATE) AS DI_DAY ,
MONTH(DI_DATE) AS DI_MONTH ,
YEAR(DI_DATE) AS DI_YEAR ,
TRANSTKH.TRH_DI,
TRANSTKH.TRH_SHIP_ADDB,
SKUMASTER.SKU_CODE ,
SKUMASTER.SKU_NAME ,
TRANSTKD.TRD_QTY,
TRANSTKD.TRD_Q_FREE,
TRANSTKD.TRD_U_PRC,
TRANSTKD.TRD_B_SELL,
TRANSTKD.TRD_B_VAT,
TRANSTKD.TRD_B_AMT

FROM 
ADDRBOOK,
ARADDRESS,
ARDETAIL,
DOCINFO ,
TRANSTKH ,
TRANSTKD ,
SKUMASTER
 
WHERE
ADDRBOOK.ADDB_COMPANY like '%" . $customer_name . "%' AND
ADDRBOOK.ADDB_SEARCH like '%" . $car_no . "%' AND
SKUMASTER.SKU_NAME like '%" . $sku_name . "%' AND
TRANSTKH.TRH_SHIP_ADDB = ADDRBOOK.ADDB_KEY AND 
(ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB) AND 
(ARDETAIL.ARD_AR = ARADDRESS.ARA_AR) AND 
(DOCINFO.DI_KEY = ARDETAIL.ARD_DI) AND 
(DOCINFO.DI_KEY = TRANSTKH.TRH_DI) AND 
(TRANSTKH.TRH_KEY = TRANSTKD.TRD_TRH) AND 
(TRANSTKD.TRD_SKU = SKUMASTER.SKU_KEY)

ORDER BY ADDRBOOK.ADDB_COMPANY , TRD_KEY DESC , SKUMASTER.SKU_CODE ";

    $stmt = $conn_sqlsvr->prepare($sql_data_select, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $rows = $stmt->rowCount();

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

## Total number of records without filtering
    $totalRecords = $rows;

## Total number of records with filtering
    $totalRecordwithFilter = $rows;

    //$myfile = fopen("qry_datas.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $totalRecords . " | " . $totalRecordwithFilter);
    //fclose($myfile);

    $query_str = $sql_data_select;

    $query = $conn_sqlsvr->prepare($query_str);
    $query->execute();

    $data = array();

    $line_no = 0;

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

        if ($_POST['sub_action'] === "GET_MASTER") {
            $TRD_QTY = $row['TRD_Q_FREE'] > 0 ? $row['TRD_QTY'] = $row['TRD_QTY'] + $row['TRD_Q_FREE'] : $row['TRD_QTY'];
            $line_no++;

            $sql_cust_string = "
            SELECT ADDRBOOK.ADDB_PHONE,ARADDRESS.ARA_ADDB
            FROM ARADDRESS
            LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
            WHERE ADDRBOOK.ADDB_COMPANY LIKE '%" . $row['ADDB_COMPANY'] . "%' AND ARADDRESS.ARA_DEFAULT = 'Y' ";
            $statement_cust_sqlsvr = $conn_sqlsvr->prepare($sql_cust_string);
            $statement_cust_sqlsvr->execute();
            while ($result_sqlsvr_cust = $statement_cust_sqlsvr->fetch(PDO::FETCH_ASSOC)) {
                $addb_phone = $result_sqlsvr_cust['ADDB_PHONE'];
            }

            $data[] = array(
                "line_no" => $line_no,
                "DI_REF" => $row['DI_REF'],
                "DI_DATE" => $row['DI_DAY'] . "/" . $row['DI_MONTH'] . "/" . $row['DI_YEAR'],
                "ADDB_COMPANY" => $row['ADDB_COMPANY'] . "  " . $addb_phone,
                "ADDB_BRANCH" => $row['ADDB_BRANCH']===null?"-":$row['ADDB_BRANCH'],
                "ADDB_ADDB" => $row['ADDB_ADDB_1'] . "-" . $row['ADDB_ADDB_2'],
                "KM" => $row['ADDB_ADDB_3'],
                "SKU_CODE" => $row['SKU_CODE'],
                "SKU_NAME" => $row['SKU_NAME'],
                "TRD_QTY" => number_format($TRD_QTY, 2),
                "TRD_B_AMT" => number_format($row['TRD_B_AMT'], 2),
                "detail" => "<button type='button' name='detail' id='" . $row['ADDB_COMPANY'] . "' class='btn btn-info btn-xs detail' data-toggle='tooltip' title='Detail'>Detail</button>"
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

