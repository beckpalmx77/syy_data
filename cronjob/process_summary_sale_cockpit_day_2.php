<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_db.php");

$year = date("Y");

$month = date("n");

$year = "2023";

for ($year = 2019; $year <= 2023; $year++) {

    for ($month = 1; $month <= 12; $month++) {

        echo $year . " | " . $month . "\n\r";

        $str_insert = "OK Insert";
        $str_update = "OK Update";

        $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

//echo $year . " - " . $month . " | " . $day . " Count <br>";

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

            for ($day_loop = 1; $day_loop <= $day; $day_loop++) {

                $sql_find = "SELECT DI_DATE  FROM ims_product_sale_cockpit 
            WHERE DI_YEAR = '" . $year . "'
            AND DI_MONTH = '" . $month . "'
            AND BRANCH = '" . $branch . "'
            AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
            AND CAST(SUBSTR(DI_DATE,1,2) AS UNSIGNED) = " . $day_loop . "
            GROUP BY DI_DATE";

                $nRows = $conn->query($sql_find)->fetchColumn();
                if ($nRows > 0) {

                    $sql_get = " SELECT BRANCH,DI_DATE,DI_YEAR,DI_MONTH,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as  TRD_G_KEYIN
                    FROM ims_product_sale_cockpit 
                    WHERE DI_YEAR = '" . $year . "'
                    AND DI_MONTH = '" . $month . "'
                    AND BRANCH = '" . $branch . "'
                    AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
                    AND CAST(SUBSTR(DI_DATE,1,2) AS UNSIGNED) = " . $day_loop . "
                    GROUP BY DI_DATE,DI_MONTH,BRANCH  
                    ORDER BY CAST(SUBSTR(DI_DATE,1,2) AS UNSIGNED) ";

                    $statement = $conn->query($sql_get);
                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);


                    foreach ($results as $result) {

                        echo $branch . " | " . $day_loop . " | " . $result['TRD_G_KEYIN'];
                        $total = $result['TRD_G_KEYIN'];

                    }

                } else {

                    echo $branch . " | " . $day_loop . " = 0 ";
                    $total = "0.00";

                }

                $sql_find_data = "SELECT day  FROM ims_product_sale_cockpit_day 
            WHERE year = '" . $year . "'
            AND month = '" . $month . "'
            AND branch = '" . $branch . "'
            AND day = " . $day_loop;

                $nRows = $conn->query($sql_find_data)->fetchColumn();
                if ($nRows <= 0) {
                    $sql_insert = "INSERT INTO ims_product_sale_cockpit_day(branch,day,month,year,total,remark) VALUES (:branch,:day,:month,:year,:total,:remark)";
                    $query = $conn->prepare($sql_insert);
                    $query->bindParam(':branch', $branch, PDO::PARAM_STR);
                    $query->bindParam(':day', $day_loop, PDO::PARAM_STR);
                    $query->bindParam(':month', $month, PDO::PARAM_STR);
                    $query->bindParam(':year', $year, PDO::PARAM_STR);
                    $query->bindParam(':total', $total, PDO::PARAM_STR);
                    $query->bindParam(':remark', $str_insert, PDO::PARAM_STR);
                    $query->execute();
                    $lastInsertId = $conn->lastInsertId();
                    if ($lastInsertId) {
                        echo " | " . $str_insert . "<br>";
                    }
                } else {
                    $sql_update = "UPDATE ims_product_sale_cockpit_day SET total=:total , remark=:remark               
            WHERE branch = :branch AND day = :day AND month = :month AND year = :year ";
                    $query = $conn->prepare($sql_update);
                    $query->bindParam(':total', $total, PDO::PARAM_STR);
                    $query->bindParam(':remark', $str_update, PDO::PARAM_STR);
                    $query->bindParam(':branch', $branch, PDO::PARAM_STR);
                    $query->bindParam(':day', $day_loop, PDO::PARAM_STR);
                    $query->bindParam(':month', $month, PDO::PARAM_STR);
                    $query->bindParam(':year', $year, PDO::PARAM_STR);
                    $query->execute();
                    echo " | " . $str_update . "<br>";
                }
            }

        }
    }

}