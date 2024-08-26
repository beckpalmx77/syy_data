<html>
<head>
    <title>TEST SQL SERVER PDO COnnect</title>
</head>
<body>
<?php

ini_set('display_errors', 1);
error_reporting(~0);

$serverName = "localhost";
$userName = "SYY";
$userPassword = "39122222";
$dbName = "SAC";

$conn = new PDO("sqlsrv:server=$serverName ; Database = $dbName", $userName, $userPassword);

if($conn)
{
    echo "Database Connected.";
}
else
{
    echo "Database Connect Failed.";
}

$conn = null;
?>
</body>
</html>
