<?php

include("../config/connect_db.php");

$date = date("Y/m/d");
$month = date("n");
$year = date("Y");

//echo $date  . " | " . $month . " | " . $year . "<br>";


//$year = "2004";

//$month = "02";

//$d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
//echo "There was $d days in February 1965.<br>";

//$year = "2022";
//$month = "12";

//$d=cal_days_in_month(CAL_GREGORIAN,2,$year);
//echo "There was $d days in February " . $year;


include('../engine/get_data_chart_dash_day2.php');





