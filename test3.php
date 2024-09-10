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
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #002d72, #003366);
            color: #f4f4f4;
            font-family: 'Roboto', sans-serif;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 300px;
            background: linear-gradient(to bottom, #1e1e1e, #2d2d2d);
            padding: 30px;
            position: fixed;
            height: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            border-right: 2px solid #444;
            transition: all 0.3s ease;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 180px;
            filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.8));
        }

        .menu {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }

        .menu li {
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .menu a {
            text-decoration: none;
            color: #f4f4f4;
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 10px;
        }

        .menu a:hover {
            color: #d4af37;
            text-shadow: 0 0 5px #d4af37;
            transform: translateX(10px);
        }

        .menu a i {
            margin-right: 10px;
        }

        .menu-icon {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            font-size: 30px;
            cursor: pointer;
            color: #f4f4f4;
        }

        .main-content {
            margin-left: 300px;
            padding: 40px;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        .main-content.shrink {
            margin-left: 0;
        }

        .header {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            border-bottom: 1px solid #777;
        }

        .header h2 {
            color: #ffffff;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        .content {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
        }

        .card {
            background: rgba(43, 43, 43, 0.7);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            padding: 20px;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.5s, box-shadow 0.5s;
        }

        .card:hover {
            transform: scale(1.05);
            background: rgba(60, 60, 60, 0.9);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.9);
        }

        .card p, .card input {
            font-size: 16px;
            color: #f4f4f4;
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .card input {
            background: none;
            border: none;
            color: #d4af37;
            font-weight: bold;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .card input:hover {
            color: #e67e22;
        }

        .card i {
            margin-right: 10px;
            color: #d4af37;
        }

        @media screen and (max-width: 768px) {
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

        @media screen and (max-width: 576px) {
            .card {
                font-size: 0.9em;
            }

            .main-content {
                padding: 10px;
            }

            .header {
                padding: 15px;
                font-size: 1.5em;
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
                // Classy color palette for the cards
                $colors = ['#2b2b2b', '#333333', '#3c3c3c', '#434343', '#4b4b4b'];
                $colorIndex = 0;

                if (mysqli_num_rows($listIoT) > 0) {
                    while ($row = mysqli_fetch_assoc($listIoT)) {
                        $color = $colors[$colorIndex % count($colors)];
                        echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-4'>";
                        echo "<div class='card' style='background-color: $color;'>";
                        echo "<form action='my_connections2.php' method='post'>
                                <input id='codeIoT' class='btn btn-link text-white' type='submit' name='codeIoT' value='{$row['codeIoT']}' title='{$row['moreDetails']}'/>
                              </form>";
                        echo "<p><i class='fas fa-user'></i> Customer: {$row['end_customer']}</p>";
                        echo "<p><i class='fas fa-info-circle'></i> Description: {$row['shrtDec']}</p>";
                        echo "<p><i class='fas fa-signal'></i> Status: {$row['currStats']}</p>";
                        echo "<p><i class='fas fa-calendar-alt'></i> Activation: {$row['instDate']}</p>";
                        echo "<p><i class='fas fa-map-marker-alt'></i> Place: {$row['place']}</p>";
                        echo "</div>";
                        echo "</div>";
                        $colorIndex++;
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
        document.querySelector('.sidebar').classList.toggle('show');
        document.querySelector('.main-content').classList.toggle('shrink');
    });
</script>

</body>
</html>
