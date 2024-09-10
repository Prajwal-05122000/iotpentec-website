<?php
session_start();

// Database Connection info.
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = 'csquare@A2023';
$tocIoT_DB = 'iot_customer';
$tocIoT_TABLE = $_SESSION['compCode'];
$DATABASE_PORT = 3306;

// Establishing the connection
$tocIoT_conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $tocIoT_DB);

// Check if the connection was successful
if (!$tocIoT_conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetching data from the database
$listIoT = mysqli_query($tocIoT_conn, "SELECT * FROM " . $tocIoT_DB . "." . $tocIoT_TABLE);

// Check if query was successful
if (!$listIoT) {
    die("Query failed: " . mysqli_error($tocIoT_conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - PentecAdmin</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Georgia', serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f2e3c6;  /* Parchment paper color */
            color: #5c3d24;  /* Dark brown text */
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            flex-grow: 1;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #8b5a2b;  /* Dark brown for sidebar */
            padding: 20px;
            position: fixed;
            height: 100%;
            transition: all 0.3s ease;
            border-right: 3px solid #d4b483;  /* Antique gold border */
        }

        .sidebar.hide {
            transform: translateX(-250px);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 150px;
            border-radius: 50%;
            border: 2px solid #d4b483;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 20px;
        }

        .menu a {
            text-decoration: none;
            color: #f8f2e1;  /* Light parchment text */
            display: flex;
            align-items: center;
            padding: 10px 0;
            transition: color 0.3s ease;
        }

        .menu a:hover {
            color: #e4cfa3;  /* Muted gold on hover */
        }

        .menu a i {
            margin-right: 10px;
        }

        /* Hamburger Menu Icon */
        .menu-icon {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            font-size: 30px;
            cursor: pointer;
            color: #5c3d24;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        .main-content.shrink {
            margin-left: 0;
        }

        .header {
            background-color: #e0c18d;  /* Soft parchment header */
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            border: 2px solid #d4b483;
            border-radius: 12px;
        }

        .header h2 {
            color: #5c3d24;
            font-size: 28px;
            font-family: 'Georgia', serif;
        }

        .content {
            background-color: #fff7e6;  /* Lighter parchment */
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #d4b483;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Card styles */
        .card {
            background-color: #f7e8c1;  /* Parchment color for cards */
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            padding: 20px;
            color: #5c3d24;
            border-left: 5px solid #d4b483;  /* Ancient golden border */
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card p {
            margin-bottom: 10px;
            font-size: 16px;
            font-family: 'Georgia', serif;
        }

        .card input {
            background: none;
            border: none;
            color: #8b5a2b;
            font-weight: bold;
            cursor: pointer;
            text-decoration: underline;
        }

        .card input:hover {
            color: #d4b483;
        }

        .card i {
            margin-right: 8px;
            color: #8b5a2b;
        }

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .menu-icon {
                display: block;
            }

            .sidebar {
                width: 250px;
                transform: translateX(-250px);
                z-index: 999;
                position: fixed;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }
        }

        /* Ensure overflow is managed */
        @media screen and (max-width: 576px) {
            .card {
                font-size: 0.9em;
            }

            .main-content {
                padding: 10px;
            }

            .header {
                padding: 15px;
                font-size: 1.2em;
            }
        }

    </style>
</head>
<body>

<!-- Menu Icon for smaller screens -->
<div class="menu-icon">
    <i class="fas fa-bars"></i>
</div>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="CSquareLogo.jpg" alt="Csquare Logo" class="logo">
        </div>
        <ul class="menu">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-database"></i> Data</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Welcome to Csquare Dashboard</h2>
        </div>
        <div class="content">
            <div class="row">
                <?php
                // Displaying each row of IoT data
                if (mysqli_num_rows($listIoT) > 0) {
                    while ($row = mysqli_fetch_assoc($listIoT)) {
                        echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-4'>";
                        echo "<div class='card'>";
                        echo "<form action='my_connections2.php' method='post'>
                                <input id='codeIoT' type='submit' name='codeIoT' value='{$row['codeIoT']}' title='{$row['moreDetails']}'/>
                              </form>";
                        echo "<p><i class='fas fa-user'></i> Customer: {$row['end_customer']}</p>";
                        echo "<p><i class='fas fa-info-circle'></i> Description: {$row['shrtDec']}</p>";
                        echo "<p><i class='fas fa-signal'></i> Status: {$row['currStats']}</p>";
                        echo "<p><i class='fas fa-calendar'></i> Activation: {$row['instDate']}</p>";
                        echo "<p><i class='fas fa-map-marker-alt'></i> Place: {$row['place']}</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No data found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('.menu-icon').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show')
        document.querySelector('.main-content').classList.toggle('shrink');
    });
</script>

</body>
</html>
