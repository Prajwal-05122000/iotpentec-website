<?php
session_start();
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = 'csquare@A2023';
$my_DB = "21002";
$DATABASE_PORT = 3306;
$iot_table = $_POST['codeIoT'];
$_SESSION['codeIoT'] = $iot_table;
?>
<?php
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root"; // Replace with your database username
$DATABASE_PASS = 'csquare@A2023'; // Replace with your database password
$my_DB = "21002"; // Replace with your database name
$IOT_DB = '21002'; // Replace with your database name
$IOT_Table = $_SESSION['codeIoT']; // Replace with your session variable for IoT table

echo "<script>console.log('In Fetch Start codeIoT = ". $_SESSION['codeIoT'] ."' );</script>";
// Connect to the iot_customer database
echo "<script>console.log('In Fetch Database Name = ". $IOT_DB ."' );</script>";
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $IOT_DB);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch run_hrs from the specified table and row
$run_hrs_result = mysqli_query($conn, "SELECT * FROM ". $IOT_Table . " WHERE `sl_no` = 1");
echo "<script>console.log('In Fetch Loaded Table = ". $IOT_Table ."' );</script>";
$parametervalue = 0; // Initialize parameter value variable
$live_data_values = [];
$table_name = $_SESSION['codeIoT']; // Initialize array for live data values
$machine_status = ""; // Variable to hold machine status message

// Example loop to fetch data from result set
foreach ($run_hrs_result as $row) :
    if ($row['sl_no'] == 1) {
        $date_time = $row['datetime'];
        $serial_number = $row['parameter1'];
        $parametervalue = $row['parameter2'];
        $bar1_label = $row['parameter3'];
        $bar1_value = $row['parameter3']; 
        $bar2_label = $row['parameter4'];
        $bar2_value = $row['parameter4']; 
        $bar3_label = $row['parameter5']; 
        $bar3_value = $row['parameter5']; 
        $bar4_label = $row['parameter6']; 
        $bar4_value = $row['parameter6']; 
        $feeder1_value = $row['parameter20'];
        $feeder2_value = $row['parameter21'];
        $feeder3_value = $row['parameter22'];
        $feeder4_value = $row['parameter23'];
        if ($serial_number == 200) {
            $machine_status = "MACHINE IS RUNNING";
        } elseif ($serial_number == 400) {
            $machine_status = "MACHINE STOPPED";
        }
        // Example: Fetching live data values
        for ($i = 7; $i <= 25; $i++) {
            $live_data_values[] = $row['parameter' . $i]; // Adjust according to your column names
        }
    }
endforeach;

echo "<script>console.log('Values in Table = ". mysqli_num_rows($run_hrs_result) ."' );</script>";

mysqli_close($conn);
echo "<script>console.log('In Fetch End codeIoT = ". $_SESSION['codeIoT'] ."' );</script>";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
        }
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .logo {
            height: 40px;
        }
        .main-container {
            display: flex;
            flex: 1;
            flex-direction: column;
        }
        
        .sidenav {
        height: 100%; /* 100% Full-height */
        width: 0; /* 0 width - change this with JavaScript */
        position: fixed; /* Stay in place */
        z-index: 1; /* Stay on top */
        top: 0; /* Stay at the top */
        left: 0;
        background-color: #111; /* Black*/
        overflow-x: hidden; /* Disable horizontal scroll */
        padding-top: 60px; /* Place content 60px from the top */
        transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
        }

        /* The navigation menu links */
        .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
        transition: 0.3s;
        }

        /* When you mouse over the navigation links, change their color */
        .sidenav a:hover {
        color: #f1f1f1;
        }

        /* Position and style the close button (top right corner) */
        .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
        }

        /* Style page content - use this if you want to push the page content to the right when you open the side navigation */
        #main {
        transition: margin-left .5s;
        padding: 20px;
        }

        .content {
            flex: 1;
            float: left;
            padding: 1px;
            margin-left: 10px;
            transition: margin-left 0.3s ease;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 250px;
        }
        .card h3 {
            margin-top: 0;
            color: #007bff;
        }
        .card p{
            font-weight: 800;
        }
        .chart-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .chart-container .card {
            flex: 1;
            min-width: 300px;
        }
        .button-container {
            display: flex;
            gap: 10px; /* Space between buttons */
        }

        .btn-stop {
            background-color: #ff4d4d;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-stop:hover {
            background-color: #ff1a1a;
        }

        .btn-run {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-run:hover {
            background-color: #45a049;
        }
        .machine-status {
        font-size: 20px;
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        }

        .machine-status.running {
            color: green;
        }

        .machine-status.stopped {
            color: red;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
        }
        .indicator-box p {
            font-size: 18px;
            color: #333;
        }
        .indicator-green {
            color: green;
        }
        .indicator-red {
            color: red;
        }
        .indicator-yellow {
            color: yellow;
        }
        .date-selection input {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: calc(100% - 22px);
        }
        .date-selection button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .date-selection button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }
        }
        @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="CSquareLogo.jpg" alt="Logo" class="logo">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; MENU</span>
    </div>
    <div class="main-container">
        <div class="content" id="content">
            <h1>Dashboard</h1>
            <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="index2.php">HOME</a>
            <a href="#">Services</a>
            <a href="#">Clients</a>
            <a href="#">Contact</a>
            </div>
            <div class="cards">
                <div class="card">
                    <h3>DEIVCE INFO</h3>
                    <p>Freezer Panel</p>
                    <p>IOT :<?php echo $_SESSION['codeIoT']?></p>
                    <p>Panel Number:7900</p>
                    <p>Serail Number : <?php echo $serial_number?></p>
                </div>
                <div class="card">
                    <h3>COMMAND</h3>
                    <div class="button-container">
                    <button id="stopButton" class="btn-stop">Stop</button>
                    <button id="Runbutton" class="btn-run">Run</button>
                </div>
                    <p class="machine-status <?php echo ($machine_status == 'MACHINE IS RUNNING') ? 'running' : 'stopped'; ?>">
                     <?php echo $machine_status; ?></p>
                </div>
                <div class="card">
                    <h3>OPERATION'S</h3>
                    <p>MOST STOPPED FLOOR -- <?php echo $live_data_values[3] ?></p>
                    <p>CURRENT LIFT POSTION -- <?php echo $live_data_values[4] ?></p>
                </div>
                <div class="card">
                    <h3>LIVE DATA</h3>
                    <ul>
                        <li>Parameter 1: <?php echo isset($live_data_values[0]) ? $live_data_values[0] . ' Celsius' : ''; ?></li>
                        <li>Parameter 2: <?php echo isset($live_data_values[1]) ? $live_data_values[1] . ' Celsius' : ''; ?></li>
                        <li>Parameter 3: <?php echo isset($live_data_values[2]) ? $live_data_values[2] . ' Celsius' : ''; ?></li>
                        <li>Parameter 4: <?php echo isset($live_data_values[3]) ? $live_data_values[3] . ' Celsius' : ''; ?></li>
                        <li>Parameter 5: <?php echo isset($live_data_values[4]) ? $live_data_values[4] . ' Celsius' : ''; ?></li>
                        <li>Parameter 6: <?php echo isset($live_data_values[5]) ? $live_data_values[5] . ' HZ' : ''; ?></li>
                        <li>Parameter 7: <?php echo isset($live_data_values[6]) ? $live_data_values[6] . ' HZ' : ''; ?></li>
                        <li>Parameter 8: <?php echo isset($live_data_values[7]) ? $live_data_values[7] . ' HZ' : ''; ?></li>
                        <li>Parameter 9: <?php echo isset($live_data_values[8]) ? $live_data_values[8] . ' HZ' : ''; ?></li>
                        <li>Parameter 10: <?php echo isset($live_data_values[9]) ? $live_data_values[9] . ' HZ' : ''; ?></li>
                    </ul>
                </div>
                <div class="card date-selection">
                    <h3>Select Date Range</h3>
                    <form method="post" action="report_file.php">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required>
                        <br>
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" required>
                        <br>
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
            <div class="chart-container">
                <div class="card">
                    <canvas id="totalRunHoursChart"></canvas>
                    <canvas id="barGraph"></canvas>
                </div>
                <div class="card">
                    <canvas id="feederDataChart"></canvas>
                </div>
                <div class="card">
                    <h3>CURRENT DATA</h3>
                    <ul>
                        <li>Parameter 1: <?php echo isset($live_data_values[0]) ? $live_data_values[0] . ' Celsius' : ''; ?>  <?php echo $date_time ?></li>
                        <li>Parameter 2: <?php echo isset($live_data_values[1]) ? $live_data_values[1] . ' Celsius' : ''; ?>  <?php echo $date_time ?></li>
                        <li>Parameter 3: <?php echo isset($live_data_values[2]) ? $live_data_values[2] . ' Celsius' : ''; ?>  <?php echo $date_time ?></li>
                        <li>Parameter 4: <?php echo isset($live_data_values[3]) ? $live_data_values[3] . ' Celsius' : ''; ?>  <?php echo $date_time ?></li>
                        <li>Parameter 5: <?php echo isset($live_data_values[4]) ? $live_data_values[4] . ' Celsius' : ''; ?>  <?php echo $date_time ?></li>
                        <li>Parameter 6: <?php echo isset($live_data_values[5]) ? $live_data_values[5] . ' HZ' : ''; ?>       <?php echo $date_time ?></li>
                        <li>Parameter 7: <?php echo isset($live_data_values[6]) ? $live_data_values[6] . ' HZ' : ''; ?>       <?php echo $date_time ?></li>
                        <li>Parameter 8: <?php echo isset($live_data_values[7]) ? $live_data_values[7] . ' HZ' : ''; ?>       <?php echo $date_time ?></li>
                        <li>Parameter 9: <?php echo isset($live_data_values[8]) ? $live_data_values[8] . ' HZ' : ''; ?>       <?php echo $date_time ?></li>
                        <li>Parameter 10: <?php echo isset($live_data_values[9]) ? $live_data_values[9] . ' HZ' : ''; ?>      <?php echo $date_time ?></li>
                    </ul>  
                </div>
            </div>
        </div>
    </div>
    <script>
            document.getElementById('stopButton').addEventListener('click', function() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_parameter.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Parameter updated successfully.');
                        location.reload();
                    } else {
                        alert('Error updating parameter.');
                    }
                }
            };
            xhr.send('new_value= 400');
        });
        document.getElementById('Runbutton').addEventListener('click', function() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_parameter.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Parameter updated successfully.');
                        location.reload();
                    } else {
                        alert('Error updating parameter.');
                    }
                }
            };
            xhr.send('new_value=200');
        });

            var ctx1 = document.getElementById('totalRunHoursChart').getContext('2d');
            var totalRunHoursChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Janurary', 'Febrauary', 'March', 'April', 'May', 'June','July','August'],
                    datasets: [{
                        label: 'Total Run Hours',
                        data: [<?php echo $bar1_label ?>, <?php echo $bar2_value?>, <?php echo $bar3_value?>, <?php echo $bar4_value?>, <?php echo $bar4_label?>],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true
                }
            });

            var ctx2 = document.getElementById('feederDataChart').getContext('2d');
            var feederDataChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ['Feeder 1', 'Feeder 2', 'Feeder 3', 'Feeder 4'],
                    datasets: [{
                        data: [25, 15, 35, 25],
                        backgroundColor: ['red', 'green', 'blue','yellow']
                    }]
                },
                options: {
                    responsive: true
                }
            });

            var ctx3 = document.getElementById('barGraph').getContext('2d');
            var barGraph = new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Weekly Data',
                        data: [<?php echo $feeder1_value ?>, <?php echo $feeder2_value ?>, <?php echo $feeder3_value?>, , 2, 3, 7],
                        backgroundColor: 'rgba(153, 102, 255, 0.6)'
                    }]
                },
                options: {
                    responsive: true
                }
            });
            function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("content").style.marginLeft = "250px";
            }

            /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
            function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("content").style.marginLeft = "0";
        }
    </script>
</body>
</html>
