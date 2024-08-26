<html>
<head>
    <title>ThaiCreate.Com PHP & SQL Server (PDO)</title>
</head>
<body>
<?php

ini_set('display_errors', 1);
error_reporting(~0);
/*
$serverName = "192.168.88.13";
$userName = "SYY";
$userPassword = "39122222";
$dbName = "SAC";
*/

include("../config/connect_sqlserver.php");

$conn_sqlsvr = new PDO("sqlsrv:server=$host ; Database = $dbname", $dbuser, $dbpass);

if($conn_sqlsvr)
{
    echo "Database Connected.";
}
else
{
    echo "Database Connect Failed.";
}

$conn_sqlsvr = null;
?>
</body>
</html>
