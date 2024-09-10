<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Connect to the database
    $DATABASE_HOST = "localhost";
    $DATABASE_USER = "root"; // Replace with your database username
    $DATABASE_PASS = 'csquare@A2023'; // Replace with your database password
    $IOT_DB = '21001'; // Replace with your database name
    $IOT_Table = 'csiot0001';

    $conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $IOT_DB);
    echo "<script>console.log('In Fetch Start codeIoT = ". $_SESSION['codeIoT'] ."' );</script>";
    echo "<script>console.log('start and end date is = ".$start_date." ');</script>";

    if ($conn->connect_error) {
        die("Connection failed: {$conn->connect_error}");
    }

    // Adjust the SQL query to fetch the relevant columns
    $sql = "SELECT * FROM $IOT_Table WHERE datetime >= '$start_date' AND datetime <= '$end_date'";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            $serial_number = $row['parameter1'];
            $most_stopped = $row['parameter2'];
            $current_floor = $row['parameter4'];
        }
    } else {
        echo "0 results";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Device Report</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .container {
            width: 100%;
            max-width: 750px; /* Adjusted to fit A4 width with margins */
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            font-weight: 400;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }
        .stats div {
            background-color: #28a745;
            color: #fff;
            border-radius: 8px;
            padding: 10px;
            flex: 1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .stats div p {
            margin: 0;
            font-size: 14px;
        }
        .stats div span {
            font-size: 20px;
            font-weight: bold;
            display: block;
        }
        .cards {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }
        .card {
            background-color: #6c757d;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            text-align: center;
        }
        .card:nth-child(2) {
            background-color: #17a2b8;
        }
        canvas {
            width: 100% !important;
            max-width: 600px; /* Adjusted for better fit on A4 */
            height: auto !important;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .footer {
            text-align: center;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Device Report</h1>
            <p>Data from <?php echo $start_date; ?> to <?php echo $end_date; ?></p>
        </div>
        
        <div class="section">
            <h2>Summary</h2>
            <div class="stats">
                <div>
                    <p>Total Records</p>
                    <span><?php echo count($data); ?></span>
                </div>
                <div>
                    <p>Start Date</p>
                    <span><?php echo $start_date; ?></span>
                </div>
                <div>
                    <p>End Date</p>
                    <span><?php echo $end_date; ?></span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Device Details and Description</h2>
            <div class="cards">
                <div class="card">
                <h3>DEVICE INFO</h3>
                    <p>LIFT PANEL</p>
                    <p>IOT: <?php echo $IOT_Table ?></p>
                    <p>Panel Number: 7900</p>
                    <p>Serial Number: <?php echo $serial_number?></p>
                </div>
                <div class="card">
                <h3>OPERATION'S</h3>
                    <p>MOST STOPPED FLOOR: <?php echo $most_stopped ?></p>
                    <p>Number of stops: <?php echo $current_floor ?></p>
                    <p>Most efficiently used day: <?php echo $current_floor ?></p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Graphs</h2>
            <canvas id="lineChart"></canvas>
            <canvas id="barChart"></canvas>
            <canvas id="radarChart"></canvas>
            <canvas id="streamgraph"></canvas>
        </div>

        <div class="section">
            <h2>Detailed Data</h2>
            <table>
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
                    <?php foreach ($data as $row) { ?>
                    <tr>
                        <td><?php echo $row['datetime']; ?></td>
                        <td><?php echo $row['parameter8']; ?></td>
                        <td><?php echo $row['parameter9']; ?></td>
                        <td><?php echo $row['parameter10']; ?></td>
                        <td><?php echo $row['parameter11']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <button onclick="window.print()">Download/Print</button>
        </div>
    </div>

    <script>
        const data = <?php echo json_encode($data); ?>;

        // Extract data for charts
        const labels = data.map(item => item.datetime);
        const values1 = data.map(item => item.parameter8);
        const values2 = data.map(item => item.parameter9);
        const values3 = data.map(item => item.parameter10);
        const values4 = data.map(item => item.parameter11);

        // Brighter colors for charts
        const brightColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
        ];

        // Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Parameter 8',
                    data: values1,
                    borderColor: brightColors[0],
                    backgroundColor: brightColors[0].replace('0.7', '0.2'),
                    borderWidth: 2,
                    pointBackgroundColor: brightColors[0],
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: brightColors[0],
                }, {
                    label: 'Parameter 9',
                    data: values2,
                    borderColor: brightColors[1],
                    backgroundColor: brightColors[1].replace('0.7', '0.2'),
                    borderWidth: 2,
                    pointBackgroundColor: brightColors[1],
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: brightColors[1],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Datetime'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Values'
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Parameter 10',
                    data: values3,
                    backgroundColor: brightColors[2],
                    borderColor: brightColors[2],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Datetime'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Values'
                        }
                    }
                }
            }
        });

        // Radar Chart
        const radarCtx = document.getElementById('radarChart').getContext('2d');
        const radarChart = new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Parameter 11',
                    data: values4,
                    backgroundColor: brightColors[3].replace('0.7', '0.2'),
                    borderColor: brightColors[3],
                    pointBackgroundColor: brightColors[3],
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: brightColors[3],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            display: false
                        },
                        suggestedMin: 0,
                        suggestedMax: Math.max(...values4)
                    }
                }
            }
        });
    </script>
</body>
</html>
