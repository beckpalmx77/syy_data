<?php

ini_set('display_errors', 1);
error_reporting(~0);

include ("../config/connect_sqlserver.php");
include ("../config/connect_db.php");


$sql_sqlsvr_shrink = "
USE SAC;

ALTER DATABASE SAC
SET RECOVERY SIMPLE;

DBCC SHRINKFILE(N'SAC', 5);

DBCC SHRINKFILE(N'SAC_log', 5);

ALTER DATABASE SAC
SET RECOVERY FULL;

";

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr_shrink);
$stmt_sqlsvr->execute();


$result = "Working At " . date('m/d/Y H:i:s', time());
$myfile = fopen("../logs/log_shrink.txt", "w") or die("Unable to open file!");
fwrite($myfile, $result);
fclose($myfile);

$sql_sqlsvr_reindex = "
USE SAC;
DECLARE @TableName VARCHAR(255);
DECLARE @sql NVARCHAR(500);
DECLARE @fillfactor INT;
SET @fillfactor = 80
DECLARE TableCursor CURSOR FOR
SELECT OBJECT_SCHEMA_NAME([object_id])+'.'+name AS TableName
FROM sys.tables
OPEN TableCursor
FETCH NEXT FROM TableCursor INTO @TableName
WHILE @@FETCH_STATUS = 0
BEGIN
SET @sql = 'ALTER INDEX ALL ON ' + @TableName + ' REBUILD WITH (FILLFACTOR = ' + CONVERT(VARCHAR(3),@fillfactor) + ')'
EXEC (@sql)
FETCH NEXT FROM TableCursor INTO @TableName
END
CLOSE TableCursor;
DEALLOCATE TableCursor;
";

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr_reindex);
$stmt_sqlsvr->execute();

$result = "Working At " . date('m/d/Y H:i:s', time());
$myfile = fopen("../logs/log_reindex.txt", "w") or die("Unable to open file!");
fwrite($myfile, $result);
fclose($myfile);

$conn_sqlsvr=null;

