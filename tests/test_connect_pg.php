<?php

date_default_timezone_set("Asia/Bangkok");
include ('../config/db_pg_value.inc');

$dsn = "pgsql:host=$host;$port;dbname=$dbname;user=$dbuser;password=$dbpass";

try{
    // create a PostgreSQL database connection
    $conn = new PDO($dsn);

    // display a message if connected to the PostgreSQL successfully
    if($conn){
        echo "Connected to the <strong>$dbname</strong> database successfully!";
    }
} catch (PDOException $e){
    // report error message
    echo $e->getMessage();
}

