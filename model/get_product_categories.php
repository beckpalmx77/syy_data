<?php
session_start();
include '../config/connect_sqlserver.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "
    SELECT ICCAT_CODE, ICCAT_NAME 
    FROM ICCAT 
    WHERE ICCAT_CODE IN (
        '1SAC01', '2SAC01', 'SAC05', '4SAC01', '3SAC01', '5SAC02', '1SAC07', 'SAC08',
        '8SAC09', '5SAC01', '999-13', '999-07', '8CPA01-001', '8CPA01-002', '8SAC11',
        '8BTCA01-001', '8BTCA01-002', '999-14', '999-08', '9SA01', '1SAC06', '6SAC08',
        '10SAC12', '1SAC02', '1SAC03', '1SAC04', '1SAC05', '1SAC09',
        '1SAC08', '1SAC10', '1SAC11', '1SAC12', '1SAC13', '1SAC14', '2SAC09', '2SAC04',
        '2SAC13', '2SAC14', '2SAC12', '2SAC02', '2SAC03', '2SAC10', '2SAC15', '2SAC06',
        '2SAC05', '2SAC07', '2SAC08', '2SAC11', '3SAC02', '3SAC06', '3SAC03', '3SAC04',
        '4SAC02', '4SAC03', '4SAC04', '4SAC06', '3SAC05', '4SAC05', '6SAC001', '2SAC16',
        '2SAC17', '2SAC18', '6SAC002', '1SAC15', '2SAC19', '2SAC20', '2SAC21'
    )
";

if (!empty($search)) {
    $query .= " AND ICCAT_NAME LIKE :search";
}

$stmt = $conn_sqlsvr->prepare($query);

if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%");
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);