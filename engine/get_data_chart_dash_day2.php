<?php

//include("config/connect_db.php");

//$year = "2023";
//$month = "1";

$start_day2 = 16;
$end_day2 = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$current_day2 = date("j");

$label2_1 = '';
$label2_2 = '';
$label2_3 = '';
$label2_4 = '';
$data2_1 = '';
$data2_2 = '';
$data2_3 = '';
$data2_4 = '';


$str_labels_return2 = "[";

for ($c_day_loop2 = $start_day2; $c_day_loop2 <= $end_day2; $c_day_loop2++) {

    if ($c_day_loop2 === $current_day2) {
        $str_labels_return2 .= $c_day_loop2;
    } else {
        $str_labels_return2 .= $c_day_loop2 . ",";
    }

}

$str_labels_return2 .= "]";


for ($y = 0; $y <= 3; $y++) {

    switch ($y) {
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

    for ($day_loop2 = $start_day2; $day_loop2 <= $current_day2; $day_loop2++) {

        $str_return2 = "[";

        $sql_get2 = "SELECT *  FROM ims_product_sale_cockpit_day 
        WHERE year = " . $year . " AND month = '" . $month . "' AND BRANCH = '" . $branch . "'                  
        ORDER BY CAST(day AS UNSIGNED) ";

        /*
        $myfile = fopen("param.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $month  . "| Year = " . $year . "| Branch" . $branch  . " : " . $sql_get2 );
        fclose($myfile);
        */


        $statement = $conn->query($sql_get2);
        $results_2 = $statement->fetchAll(PDO::FETCH_ASSOC);


        foreach ($results_2 as $result_2) {
            if ((int)$result_2['day']>=$start_day2) {

                if ((int)$result_2['day'] === $current_day2) {
                    $str_return2 .= $result_2['total'];
                } else {
                    $str_return2 .= $result_2['total'] . ",";
                }
            }
        }

        $str_return2 .= "]";

        //echo "str_return = " . $str_return2 . "<br>";

        switch ($y) {
            case 0:
                $label2_1 = "CP-340";
                $data2_1 = $str_return2;
                break;
            case 1:
                $label2_2 = "CP-BY";
                $data2_2 = $str_return2;
                break;
            case 2:
                $label2_3 = "CP-BB";
                $data2_3 = $str_return2;
                break;
            case 3:
                $label2_4 = "CP-RP";
                $data2_4 = $str_return2;
                break;
        }

    }
}


$labels_2 = $str_labels_return2 ;

/*
echo "labels = " . $labels . "<br>";

echo "label1 = " . $label1 . "<br>";
echo "label2 = " . $label2 . "<br>";
echo "label3 = " . $label3 . "<br>";
echo "label4 = " . $label4 . "<br>";

echo "data1 = " . $data1 . "<br>";
echo "data2 = " . $data2 . "<br>";
echo "data3 = " . $data3 . "<br>";
echo "data4 = " . $data4 . "<br>";
*/





