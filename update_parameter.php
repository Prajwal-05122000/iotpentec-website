<?php
session_start();

$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = 'csquare@A2023';
$DATABASE_NAME = "21001";
$IOT_Table = $_SESSION['codeIoT'];

$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_value = $_POST['new_value'];

    $stmt = $conn->prepare("UPDATE $IOT_Table SET parameter1 = ? WHERE sl_no = 1");
    $stmt->bind_param('i', $new_value);

    if ($stmt->execute()) {
        echo "Parameter updated successfully.";
    } else {
        echo "Error updating parameter: {$stmt->error}";
    }

    $stmt->close();
}

$conn->close();
