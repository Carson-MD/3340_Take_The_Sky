<!DOCTYPE html>
<html>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-174454246-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-174454246-1');
</script>
    <title>Take the Sky - On Demand</title>
    <link rel="icon" href="../images/logo_icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset = "utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>  
    <script src="../js/validation.js"></script>
    <style>
        .on-demand {
            position: relative;
            text-align: center;
            padding: 0px;
        }
        .demand-text {
            position: absolute;
            top: 47%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
<?php
    session_start();
    require_once 'authentication.php';

    
    if($_SESSION['usertype']==2) // Use this to differentiate between user types (using ==)
    {
        if (isset($_GET['trip_type'])  &&
            isset($_GET['passengers']) && // Put everything that happens after user submits form in here
            isset($_GET['plane_type']) &&
            isset($_GET['departure'])  &&
            isset($_GET['arrival'])    &&
            isset($_GET['dep_date'])   &&
            isset($_GET['arr_date'])) // Put everything that happens after user submits form in here
        {
            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error) die($conn->connect_error);
            
            $trip_type = $_GET['trip_type'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $trip_type_clean = mysql_entities_fix_string($conn, $trip_type);
            $passengers = $_GET['passengers'];
            $plane_type = $_GET['plane_type'];
            $departure = $_GET['departure'];
            $arrival = $_GET['arrival'];
            $dep_date = $_GET['dep_date'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $dep_date_clean = mysql_entities_fix_string($conn, $dep_date);
            $arr_date = $_GET['arr_date'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $arr_date_clean = mysql_entities_fix_string($conn, $arr_date);// -> clean the inputs before sending them to SQL
            $username = $_SESSION['username'];
            
            if ($passengers=="1"){
            
                if ($trip_type=="1"){
                    $queries = "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                    "INSERT INTO `MAKES`(`PLN`, `FID`) VALUES ((SELECT PLN FROM USERS WHERE UNAME='$username'),(SELECT MAX(FID) FROM FLIGHT));".
                    "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                    "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                    "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                    "UPDATE `PILOT`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PILOT.PLN=USERS.PLN AND UNAME='$username';".
                    "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                    "INSERT INTO `MAKES`(`PLN`, `FID`) VALUES ((SELECT PLN FROM USERS WHERE UNAME='$username'),(SELECT MAX(FID) FROM FLIGHT));".
                    "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                    "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                    "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                    "UPDATE `PILOT`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PILOT.PLN=USERS.PLN AND UNAME='$username';";
                    $result = $conn->multi_query($queries);
                    if (!$result) die($conn->error);
                }
                
                else{
                    $queries = "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                    "INSERT INTO `MAKES`(`PLN`, `FID`) VALUES ((SELECT PLN FROM USERS WHERE UNAME='$username'),(SELECT MAX(FID) FROM FLIGHT));".
                    "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                    "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                    "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                    "UPDATE `PILOT`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PILOT.PLN=USERS.PLN AND UNAME='$username';";
                    $result = $conn->multi_query($queries);
                    if (!$result) die($conn->error);
                }
            }
            
            else {
                
                if ($trip_type=="1"){
                    $queries = "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                    "INSERT INTO `JOINS`(`SEAT`, `FID`, `PLN`) VALUES ('$passengers',(SELECT MAX(FID) FROM FLIGHT),(SELECT PLN FROM USERS WHERE UNAME='$username'));".
                    "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                    "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                    "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                    "UPDATE `PILOT`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PILOT.PLN=USERS.PLN AND UNAME='$username';".
                    "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                    "INSERT INTO `JOINS`(`SEAT`, `FID`, `PLN`) VALUES ('$passengers',(SELECT MAX(FID) FROM FLIGHT),(SELECT PLN FROM USERS WHERE UNAME='$username'));".
                    "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                    "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                    "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                    "UPDATE `PILOT`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PILOT.PLN=USERS.PLN AND UNAME='$username';";
                    $result = $conn->multi_query($queries);
                    if (!$result) die($conn->error);
                }
            
            else{
                    $queries = "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                    "INSERT INTO `JOINS`(`SEAT`, `FID`, `PLN`) VALUES ('$passengers',(SELECT MAX(FID) FROM FLIGHT),(SELECT PLN FROM USERS WHERE UNAME='$username'));".
                    "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                    "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                    "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                    "UPDATE `PILOT`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PILOT.PLN=USERS.PLN AND UNAME='$username';";
                    $result = $conn->multi_query($queries);
                    if (!$result) die($conn->error);
                }
            }
        }

    }
    
    elseif($_SESSION['usertype']==3) // Use this to differentiate between user types (using ==)
    {
        if (isset($_GET['trip_type'])  &&
            isset($_GET['passengers']) && // Put everything that happens after user submits form in here
            isset($_GET['plane_type']) &&
            isset($_GET['departure'])  &&
            isset($_GET['arrival'])    &&
            isset($_GET['dep_date'])   &&
            isset($_GET['arr_date'])) // Put everything that happens after user submits form in here
        {
            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error) die($conn->connect_error);

            $trip_type = $_GET['trip_type'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $trip_type_clean = mysql_entities_fix_string($conn, $trip_type);
            $passengers = $_GET['passengers'];
            $plane_type = $_GET['plane_type'];
            $departure = $_GET['departure'];
            $arrival = $_GET['arrival'];
            $dep_date = $_GET['dep_date'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $dep_date_clean = mysql_entities_fix_string($conn, $dep_date);
            $arr_date = $_GET['arr_date'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $arr_date_clean = mysql_entities_fix_string($conn, $arr_date);// -> clean the inputs before sending them to SQL
            $username = $_SESSION['username'];
            
            if ($trip_type=="1"){
                $queries = "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                "INSERT INTO `JOINS`(`SEAT`, `FID`, `PSID`) VALUES ('$passengers',(SELECT MAX(FID) FROM FLIGHT),(SELECT PSID FROM USERS WHERE UNAME='$username'));".
                "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                "UPDATE `PASSENGER`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PASSENGER.PSID=USERS.PSID AND UNAME='$username';".
                "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                "INSERT INTO `JOINS`(`SEAT`, `FID`, `PSID`) VALUES ('$passengers',(SELECT MAX(FID) FROM FLIGHT),(SELECT PSID FROM USERS WHERE UNAME='$username'));".
                "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                "UPDATE `PASSENGER`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PASSENGER.PSID=USERS.PSID AND UNAME='$username';";
                $result = $conn->multi_query($queries);
                if (!$result) die($conn->error);
            }
            
            else{
                $queries = "INSERT INTO `FLIGHT`(`FDATE`) VALUES (DATE '$dep_date_clean');".
                "INSERT INTO `JOINS`(`SEAT`, `FID`, `PSID`) VALUES ('$passengers',(SELECT MAX(FID) FROM FLIGHT),(SELECT PSID FROM USERS WHERE UNAME='$username'));".
                "INSERT INTO `FROMM`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$departure');".
                "INSERT INTO `TOO`(`FID`, `AID`) VALUES ((SELECT MAX(FID) FROM FLIGHT), '$arrival');".
                "INSERT INTO `USINGG`(`PID`, `FID`) VALUES ('$plane_type',(SELECT MAX(FID) FROM FLIGHT));".
                "UPDATE `PASSENGER`, `USERS` SET `TFLIGHT`=TFLIGHT+1 WHERE PASSENGER.PSID=USERS.PSID AND UNAME='$username';";
                $result = $conn->multi_query($queries);
                if (!$result) die($conn->error);
            }
        }

    }

    function mysql_entities_fix_string($conn, $string)
    {
        return htmlentities(mysql_fix_string($conn, $string));
    }
    
    
    function mysql_fix_string($conn, $string)
    {
        if (get_magic_quotes_gpc()) 
            $string = stripslashes($string);
            
        return $conn->real_escape_string($string);
    }
?>
    <nav class="navbar navbar-expand-sm bg-white navbar-light">
        <a href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/landing_page.php">
            <img src="../images/logo.jpg" alt="Take the Sky logo" style="height:75px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav nav-fill w-100">
                <li class="nav-item mx-3">
                    <a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/scheduled.php">Scheduled Flights</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/on_demand.php">On Demand</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/contact_us.php">Contact Us</a>
                </li>
                <li class="nav-item mx-3">
                    <?php
                    if (isset($_SESSION['usertype']))
                        echo '<a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/logout.php">Logout</a>';
                    else
                        echo '<a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/register.php">Register</a>';
                    ?>
                </li>
                <li class="nav-item mx-3">
                    <?php
                    if ($_SESSION['usertype']==2 || $_SESSION['usertype']==3)
                        echo '<a class="nav-link" href="#">Welcome Back ' .  $_SESSION['username'] . ' !</a>';
                    elseif($_SESSION['usertype'] == 1)
                        echo '<a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/dashboard.php">Dashboard</a>';
                    else
                        echo '<a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/login.php">Login</a>';
                    ?>
                </li>
            </ul>
        </div>  
    </nav>    
    
    <div class="container on-demand">
        <img src="../images/beach.png" alt="Pastel beach in blues" style="width:100%;">
        <div class="demand-text"><h2>On Demand</h2></div>
    </div>
    <div class="container" style="border-style: ridge; border-radius: 5px; padding:10px;">
        <form action="on_demand.php" method="get">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3 align-self-center">
                        <select class="browser-default custom-select" name="departure" aria-label="departure airport">
                            <option class="hidden" selected disabled>Select Airport</option>
                            <option value="CYQG">Windsor International Airport</option>
                            <option value="CYTZ">Billy Bishop Toronto City Airport</option>
                            <option value="CYXU">London International Airport</option>
                            <option value="CYYZ">Toronto Pearson International Airport</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-center">
                        <select class="browser-default custom-select" name="arrival" aria-label="arrival airport">
                            <option class="hidden" selected disabled>Select Airport</option>
                            <option value="CYQG">Windsor International Airport</option>
                            <option value="CYTZ">Billy Bishop Toronto City Airport</option>
                            <option value="CYXU">London International Airport</option>
                            <option value="CYYZ">Toronto Pearson International Airport</option>
                        </select>
                    </div>
                    <div class="col-md-1 align-self-center">
                    <b>Departing:</b>
                    </div>
                    <div class="col-md-2 align-self-center">
                        <input type="date" class="form-control" id="dep_date" name="dep_date" aria-label="Date of departure">
                    </div>
                    <div class="col-md-1 align-self-center">
                    <b>Arriving:</b>
                    </div>
                    <div class="col-md-2 align-self-center">
                        <input type="date" class="form-control" id="arr_date" name="arr_date" aria-label="Arrival date">    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 align-self-center">
                        <select class="browser-default custom-select" name="trip_type" aria-label="One or two way trip">
                            <option class="hidden" selected disabled>Trip Type</option>
                            <option value="1">Round Trip</option>
                            <option value="2">One Way</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-center">
                        <select class="browser-default custom-select" name="plane_type" id="model" onfocusout="set_seats() " aria-label="plane type">
                            <option class="hidden" selected disabled>Plane Type</option>
                            <option value="1">Cessna 152</option>
                            <option value="2">Cessna 172</option>
                            <option value="3">Cessna 182</option>
                        </select>
                    </div>
                     <div class="col-md-3 align-self-center">
                        <select class="browser-default custom-select" name="passengers" id="passengers" aria-label="seat choice">
                            <option class="hidden" selected disabled>Seat Choice</option>
                            <option value="4">Seat 4</option>
                            <option value="3">Seat 3</option>
                            <option value="2">Seat 2</option>
                            <?php
                            if ($_SESSION['usertype'] == 2)
                                echo '<option value="1">Pilot in Command</option>';
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-center" align="end">
                    <?php
                        if (isset($_GET['departure']))
                        {
                            if ($_SESSION['usertype']==2 || $_SESSION['usertype']==2)
                                echo "<span style='color:green'><b>- Flight Created -</b></span>";
                            else 
                                echo "<span style='color:red'><b>- Please Sign In -</b></span>";
                        }
                    ?>
                        <input type="submit" class="btn btn-primary" value="Add Flight" aria-label="Add route"/>
                    </div>
                    <?php
                        if ($added)
                            echo "<p class='text-success'><h4>Route added!<h4><p>"
                    ?>
                </div>
            </div>
        </form>
    </div>
</body>