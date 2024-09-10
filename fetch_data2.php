<?php
header('Content-Type: application/json');
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = 'csquare@A2023';
$DATABASE_NAME = "21001";

$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$input = json_decode(file_get_contents('php://input'), true);
$startDate = $input['startDate'];
$endDate = $input['endDate'];

$query = "SELECT `datetime`, `parameter1`, `parameter2`, `parameter3`, `parameter4`, `parameter5`, `parameter6`, `parameter7` 
          FROM `iot_table` 
          WHERE `datetime` BETWEEN '$startDate' AND '$endDate'";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data['labels'][] = $row['datetime'];
    $data['param1'][] = $row['parameter1'];
    $data['param2'][] = $row['parameter2'];
    $data['param3'][] = $row['parameter3'];
    $data['param4'][] = $row['parameter4'];
    $data['param5'][] = $row['parameter5'];
    $data['param6'][] = $row['parameter6'];
    $data['param7'][] = $row['parameter7'];
}

mysqli_close($conn);
echo json_encode($data);
