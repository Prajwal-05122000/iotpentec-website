<?php 

    //error_reporting('E_ALL');
    session_start();

    // Database Connection info.
    $DATABASE_HOST = "localhost";
    $DATABASE_USER = "root";
    $DATABASE_PASS = 'csquare@A2023';
    $DATABASE_NAME = 'accounts';
    $DATABASE_PORT = 3306;
    
    // Now we check if the data from the login form was submitted, isset() will check if the data exists.
    if (isset($_POST['Register']))
    {
        echo "<script>console.log('Register Pressed' );</script>";
        header('Location: ../register.php');
        exit;
        # Publish-button was clicked
    }
    elseif (isset($_POST['Login'])) 
    {
        $PREPARED_SCHEMA_NAME = "SELECT * FROM actdir WHERE loginID = ?";

        // Try and connect using the info above.
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        if (mysqli_connect_errno())
        {
            // If there is an error with the connection, stop the script and display the error.
            echo "<script>console.log('Login DataBase Connection Failed' );</script>";
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        echo "<script>console.log('Login DataBase Connected Successfully' );</script>";

        // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
        if ($stmt = $con->prepare($PREPARED_SCHEMA_NAME))
        {
            //echo nl2br("\ntable connected successfully");
            echo "<script>console.log('Login Table Connected Successfully' );</script>";
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	        $stmt->bind_param("s", $_POST['username']);
	        $stmt->execute();
            echo "<script>console.log('execution done' );</script>";
	        
            // Store the result so we can check if the account exists in the database.
	        $stmt->store_result();
            
            if ($stmt->num_rows() > 0)
            {
                $stmt->bind_result($d01, $d02, $d03, $d04, $d05, $d06, $d07, $d08, $d09, $d10, $d11, $d12, $d13, $d14, $d15, $d16, $d17, $d18, $d19, $d20, $d21, $d22, $d23, $d24, $d25, $d26, $d27, $d28, $d29, $d30, $d31, $d32, $d33, $d34, $d35, $d36, $d37, $d38, $d39, $d40, $d41, $d42, $d43, $d44);
                $stmt->fetch();
                //echo nl2br("\ndata fetched successfully");
                // Account exists, now we verify the password.
                // Note: remember to use password_hash in your registration file to store the hashed passwords.
                if ($_POST['password'] === $d08 && $_POST['organisation'] === $d02)
                {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but store the data on the server.
                    //echo nl2br("\npassword verified successfully");
                    session_regenerate_id();
                    $_SESSION['compPost'] = $_POST['organisation'];
                    $_SESSION['userID'] = $d01;
                    $_SESSION['compCode'] = $d02;
                    $_SESSION['salute'] = $d03;
                    $_SESSION['firstname'] = $d04;
                    $_SESSION['midname'] = $d05;
                    $_SESSION['lastname'] = $d06;
                    $_SESSION['loginID'] = $d07;
                    $_SESSION['passWoard'] = $d08;
                    $_SESSION['dob'] = $d09;
                    $_SESSION['doj'] = $d10;
                    $_SESSION['gender'] = $d11;
                    $_SESSION['maritalStats'] = $d12;
                    $_SESSION['designation'] = $d13;
                    $_SESSION['userRole'] = $d14;
                    $_SESSION['offEmail'] = $d15;
                    $_SESSION['perEmail'] = $d16;
                    $_SESSION['ccodeOHP'] = $d17;
                    $_SESSION['officeHP'] = $d18;
                    $_SESSION['ccodeOLL'] = $d19;
                    $_SESSION['officeLL'] = $d20;
                    $_SESSION['ccodePHP'] = $d21;
                    $_SESSION['personalHP'] = $d22;
                    $_SESSION['ccodePLL'] = $d23;
                    $_SESSION['personalLL'] = $d24;
                    $_SESSION['officeAddrL1'] = $d25;
                    $_SESSION['officeAddrL2'] = $d26;
                    $_SESSION['officeAddrL3'] = $d27;
                    $_SESSION['offCountry'] = $d28;
                    $_SESSION['offStateProvince'] = $d29;
                    $_SESSION['offCity'] = $d30;
                    $_SESSION['offZip'] = $d31;
                    $_SESSION['perAddrL1'] = $d32;
                    $_SESSION['perAddrL2'] = $d33;
                    $_SESSION['perAddrL3'] = $d34;
                    $_SESSION['perCountry'] = $d35;
                    $_SESSION['perStateProvince'] = $d36;
                    $_SESSION['perCity'] = $d37;
                    $_SESSION['perZip'] = $d38;
                    $_SESSION['doApp'] = $d39;
                    $_SESSION['doApr'] = $d40;
                    $_SESSION['doAct'] = $d41;
                    $_SESSION['doDeAct'] = $d42;
                    $_SESSION['deactReason'] = $d43;
                    $_SESSION['compName'] = $d44;
    
                    //echo "Welcome ".$_SESSION['slno']."	".$_SESSION['salute']."	".$_SESSION['firstname']." ".$_SESSION['midname']." ".$_SESSION['lastname']." ".$_SESSION['loginID']." ".$_SESSION['dob']." ".$_SESSION['doj']." ".$_SESSION['sex']." ".$_SESSION['designation']." ".$_SESSION['userRole']." ".$_SESSION['offEmail']." ".$_SESSION['perEmail']." ".$_SESSION['ccodeOH']." ".$_SESSION['officeHandphone']." ".$_SESSION['ccodeOL']." ".$_SESSION['officeLandphone']." ".$_SESSION['ccodePH']." ".$_SESSION['perHandphone']." ".$_SESSION['ccodeRL']." ".$_SESSION['resLandphone']." ".$_SESSION['doapplication']." ".$_SESSION['doactivation']." ".$_SESSION['dodeavtivation']." ".$_SESSION['addrl1']." ".$_SESSION['addrl2']." ".$_SESSION['addrl3']." ".$_SESSION['city']." ".$_SESSION['stateProvince']." ".$_SESSION['country']." ".$_SESSION['zip']." ".$_SESSION['accStatus']." ".$_post['passWoard']." ".$passWoard;
                    $REDIR_URL = "../pages/".$_SESSION['userRole']."/index2.php";
                    echo $REDIR_URL;
                    $stmt->close();
                    header ('Location: '.$REDIR_URL);
                    exit;
                }
                else
                {
                    // Incorrect password
                    echo "Incorrect password!";
                    $_SESSION['warning'] = "Incorrect Password";
                    $_SESSION['message'] = "Incorrect Password";
                    //$message = $_SESSION['warning'];
                    $stmt->close();
                    //sleep(5);
                    header('Location: ../index2.php');
                    exit;
                }
            }
            else
            {
                // Incorrect username
                echo "Incorrect username!";
                $_SESSION['warning'] = "Username Not Found";
                $_SESSION['message'] = "Incorrect UserName";
                //$message = $_SESSION['warning'];
                $stmt->close();
                //sleep(5);
                header('Location: ../index.php');
                exit;
            }
            
        }
    }
