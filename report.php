<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iot_number = $_POST['iot_number'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', 'csquare@A2023', '21002'); // Update your credentials

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM csiot0001 WHERE datetime >= '$start_date' AND datetime <= '$end_date' AND iot_number = '$iot_number'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the same CSS file -->
    <style>
        .print-button {
            margin: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="content">
        <h1>Report for IoT Number: <?php echo htmlspecialchars($iot_number); ?></h1>
        <p>From: <?php echo htmlspecialchars($start_date); ?> To: <?php echo htmlspecialchars($end_date); ?></p>

        <?php if ($result && $result->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Datetime</th>
                        <th>Parameter 8</th>
                        <th>Parameter 9</th>
                        <th>Parameter 10</th>
                        <th>Parameter 11</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['datetime']); ?></td>
                            <td><?php echo htmlspecialchars($row['parameter8']); ?></td>
                            <td><?php echo htmlspecialchars($row['parameter9']); ?></td>
                            <td><?php echo htmlspecialchars($row['parameter10']); ?></td>
                            <td><?php echo htmlspecialchars($row['parameter11']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No data found for the selected criteria.</p>
        <?php endif; ?>

        <button class="print-button" onclick="printReport()">Print Report</button>
    </div>
</body>
</html>
