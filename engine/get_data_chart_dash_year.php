<?php
//$year = '2022';
$label1 = '';
$label2 = '';
$label3 = '';
$label4 = '';
$data1 = '';
$data2 = '';
$data3 = '';
$data4 = '';

for ($x = 0; $x <= 3; $x++) {

    switch ($x) {
        case 0:
            $branch = "CP-340";
            break;
        case 1:
            $branch = "CP-BY";
            break;
        case 2:
            $branch = "CP-BB";
            break;
        case 3:
            $branch = "CP-RP";
            break;
    }

    $str_return = "[";

    $sql_get = " SELECT BRANCH,DI_YEAR,DI_MONTH,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as  TRD_G_KEYIN
 FROM ims_product_sale_cockpit 
 WHERE DI_YEAR = '" . $year . "'
 AND BRANCH = '" . $branch . "'
 AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
 GROUP BY DI_MONTH,BRANCH  
 ORDER BY BRANCH,CAST(DI_MONTH AS UNSIGNED) ";

    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);


    foreach ($results as $result) {
        if ($result['DI_MONTH'] == 12) {
            $str_return .= $result['TRD_G_KEYIN'];
        } else {
            $str_return .= $result['TRD_G_KEYIN'] . ",";
        }
    }

    $str_return .= "]";

    switch ($x) {
        case 0:
            $label1 = "CP-340";
            $data1 = $str_return;
            break;
        case 1:
            $label2 = "CP-BY";
            $data2 = $str_return;
            break;
        case 2:
            $label3 = "CP-BB";
            $data3 = $str_return;
            break;
        case 3:
            $label4 = "CP-RP";
            $data4 = $str_return;
            break;
    }

}

/*
echo $label1 . " ";
echo $label2 . " ";
echo $label3 . " ";
echo $label4 . " ";

*/

/*
echo $data1 . "<br>";
echo $data2 . "<br>";
echo $data3 . "<br>";
echo $data4 . "<br>";
echo $year  .  "<br>";
*/

