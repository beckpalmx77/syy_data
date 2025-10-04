<?php
header('Content-Type: application/json');

include("../config/connect_db.php");

$month = date("n");
$year = date("Y");

//$myfile = fopen("param_month.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $month  . "| Year = " . $year);
//fclose($myfile);

$sql_get = "
 SELECT BRANCH,branch_name,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as  TRD_G_KEYIN
 FROM v_ims_product_sale_cockpit 
 WHERE DI_YEAR = '" . $year .  "' AND DI_MONTH = '" . $month .  "'
 AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
 GROUP BY  BRANCH
 ORDER BY BRANCH
";

//$myfile = fopen("qry_file1.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_get);
//fclose($myfile);

$return_arr = array();

$statement = $conn->query($sql_get);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    $return_arr[] = array("BRANCH" => $result['branch_name'],
        "TRD_G_KEYIN" => $result['TRD_G_KEYIN']);

}

echo json_encode($return_arr);
