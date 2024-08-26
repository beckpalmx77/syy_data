<?php

$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$end_day = "15";

$current_day = date("j");

//$current_day = 31;

$label1 = '';
$label2 = '';
$label3 = '';
$label4 = '';
$data1 = '';
$data2 = '';
$data3 = '';
$data4 = '';

$str_labels_return = "[";

for ($c_day_loop = 1; $c_day_loop <= $current_day; $c_day_loop++) {

    if ($c_day_loop === $current_day) {
        $str_labels_return .= $c_day_loop;
    } else {
        $str_labels_return .= $c_day_loop . ",";
    }

}

$str_labels_return .= "]";


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

    for ($day_loop = 1; $day_loop <= $current_day; $day_loop++) {

        $str_return = "[";

        $sql_get = "SELECT *  FROM ims_product_sale_cockpit_day 
        WHERE year = " . $year . " AND month = '" . $month . "' AND BRANCH = '" . $branch . "'                  
        ORDER BY CAST(day AS UNSIGNED) ";


        $statement = $conn->query($sql_get);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);


        foreach ($results as $result) {
            if ((int)$result['day'] === $current_day) {
                $str_return .= $result['total'];
            } else {
                $str_return .= $result['total'] . ",";
            }
        }

        $str_return .= "]";

        //echo "str_return = " . $str_return . "<br>";

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
}


$labels = $str_labels_return ;

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





