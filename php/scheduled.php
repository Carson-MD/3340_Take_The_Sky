<!DOCTYPE html>
<html lang = "en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-174454246-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-174454246-1');
</script>
    <title>Take The Sky - Scheduled</title>
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
        .scheduled {
            position: relative;
            text-align: center;
            padding: 0px;
        }
        .scheduled-text {
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
    require_once 'sanitize.php';
    $user_type = $_SESSION['usertype'];
    $user_name = $_SESSION['username'];

        if (isset($_GET['departure'])  &&
            isset($_GET['arrival'])    && // Put everything that happens after user submits form in here
            isset($_GET['date'])       &&
            isset($_GET['model'])      &&
            isset($_GET['seat'])) // Put everything that happens after user submits form in here
        {
            
            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error) die($conn->connect_error);
            
            $departure = $_GET['departure'];                                         // -> draw value from post (or from $_SESSION['usertype'] or ['username'])
            $arrival = $_GET['arrival'];
            $date = $_GET['date'];
            $date_clean = mysql_entities_fix_string($conn, $date);
            $model = $_GET['model'];
            $seat = $_GET['seat'];
    
            $queries = "DROP TABLE RESULTS;

            DROP TABLE SEAT2PAX;
            DROP TABLE SEAT2PLT;
            DROP TABLE SEAT2;
            
            DROP TABLE SEAT3PAX;
            DROP TABLE SEAT3PLT;
            DROP TABLE SEAT3;
            
            DROP TABLE SEAT4PAX;
            DROP TABLE SEAT4PLT;
            DROP TABLE SEAT4;
            
            DROP TABLE PICDFLTS;
            DROP TABLE FLIGHTS;
            
            CREATE TABLE FLIGHTS AS
            SELECT DISTINCT FLIGHT.FID, FROMM.AID AS FM, TOO.AID AS TT, FLIGHT.FDATE AS DT, PLANE.PTYPE AS PT
            FROM `FLIGHT`, `FROMM`, `TOO`, `USINGG`, `PLANE`, `MAKES`, `PILOT`, `JOINS`
            WHERE FROMM.AID LIKE '$departure' AND TOO.AID LIKE '$arrival' AND FLIGHT.FDATE= (DATE '$date_clean') AND USINGG.PID LIKE '$model' AND FLIGHT.FID=FROMM.FID AND FLIGHT.FID=TOO.FID AND FLIGHT.FID=USINGG.FID AND USINGG.PID=PLANE.PID AND (FLIGHT.FID=JOINS.FID OR FLIGHT.FID=MAKES.FID);
            
            CREATE TABLE PICDFLTS AS SELECT FLIGHTS.FID AS FID, MAKES.PLN, PILOT.RATING AS RTG FROM FLIGHTS, MAKES, PILOT WHERE FLIGHTS.FID=MAKES.FID AND MAKES.PLN=PILOT.PLN;
            
            CREATE TABLE SEAT2PAX AS SELECT FLIGHTS.FID AS FID, JOINS.PSID, RATING AS RTG FROM JOINS, FLIGHTS, PASSENGER WHERE JOINS.FID=FLIGHTS.FID AND SEAT=2 AND JOINS.PSID!=0 AND JOINS.PSID=PASSENGER.PSID;
            CREATE TABLE SEAT2PLT AS SELECT FLIGHTS.FID AS FID, JOINS.PLN, RATING AS RTG FROM JOINS, FLIGHTS, PILOT WHERE JOINS.FID=FLIGHTS.FID AND SEAT=2 AND JOINS.PLN!='$' AND JOINS.PLN=PILOT.PLN;
            CREATE TABLE SEAT2 AS SELECT FID, RTG FROM SEAT2PAX UNION SELECT FID, RTG FROM SEAT2PLT;
            
            CREATE TABLE SEAT3PAX AS SELECT FLIGHTS.FID AS FID, JOINS.PSID, RATING AS RTG FROM JOINS, FLIGHTS, PASSENGER WHERE JOINS.FID=FLIGHTS.FID AND SEAT=3 AND JOINS.PSID!=0 AND JOINS.PSID=PASSENGER.PSID;
            CREATE TABLE SEAT3PLT AS SELECT FLIGHTS.FID AS FID, JOINS.PLN, RATING AS RTG FROM JOINS, FLIGHTS, PILOT WHERE JOINS.FID=FLIGHTS.FID AND SEAT=3 AND JOINS.PLN!='$' AND JOINS.PLN=PILOT.PLN;
            CREATE TABLE SEAT3 AS SELECT FID, RTG FROM SEAT3PAX UNION SELECT FID, RTG FROM SEAT3PLT;
            
            CREATE TABLE SEAT4PAX AS SELECT FLIGHTS.FID AS FID, JOINS.PSID, RATING AS RTG FROM JOINS, FLIGHTS, PASSENGER WHERE JOINS.FID=FLIGHTS.FID AND SEAT=4 AND JOINS.PSID!=0 AND JOINS.PSID=PASSENGER.PSID;
            CREATE TABLE SEAT4PLT AS SELECT FLIGHTS.FID AS FID, JOINS.PLN, RATING AS RTG FROM JOINS, FLIGHTS, PILOT WHERE JOINS.FID=FLIGHTS.FID AND SEAT=4 AND JOINS.PLN!='$' AND JOINS.PLN=PILOT.PLN;
            CREATE TABLE SEAT4 AS SELECT FID, RTG FROM SEAT4PAX UNION SELECT FID, RTG FROM SEAT4PLT;
            
            CREATE TABLE RESULTS AS
            SELECT FLIGHTS.FID, FLIGHTS.FM, FLIGHTS.TT, FLIGHTS.DT, FLIGHTS.PT, PICDFLTS.RTG AS PLTRTG, SEAT2.RTG AS S2RTG, SEAT3.RTG AS S3RTG, SEAT4.RTG AS S4RTG
            FROM FLIGHTS
            LEFT OUTER JOIN PICDFLTS ON FLIGHTS.FID=PICDFLTS.FID
            LEFT OUTER JOIN SEAT2 ON FLIGHTS.FID=SEAT2.FID
            LEFT OUTER JOIN SEAT3 ON FLIGHTS.FID=SEAT3.FID
            LEFT OUTER JOIN SEAT4 ON FLIGHTS.FID=SEAT4.FID;
            
            $seat;";    
            
            
            $conn->multi_query($queries);
            $conn->close();
            sleep(8);
            $conn = new mysqli($hn, $un, $pw, $db);
            if ($conn->connect_error) 
                die($conn->connect_error);


            $new_query = "SELECT * FROM RESULTS WHERE 1;";
            $new_result = $conn->query($new_query);
            if (!$new_result)
                $error = true;

            $rows = $new_result->num_rows;

        }   
        
        elseif (isset($_GET['passengers']))
        {
            if ($user_type == 2 || $user_type == 3)
            {
                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) 
                    die($conn->connect_error);
                
                $query = $_GET['passengers'];
                
                $result = $conn->query($query);
                if (!$result) 
                    die($conn->error);
                
                $insertion = true;
            }
            else
               $guest = true; 
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
    
    <div class="container scheduled">
        <img src="../images/snowy-scene.svg" alt="Mother and child wave to passing plane" style="width:100%">
        <div class="scheduled-text"><h2>Scheduled Flights</h2></div>
    </div>
    <div class="container" style="5px; padding-top:10px;">
        <form action="scheduled.php" method="get">
        <div class="form-group">
            <div class = "row justify-content-center">
                <div class="col-sm-3" align="center">
                    <select class="browser-default custom-select" name="departure" aria-label="departure airport">
                        <option class="hidden" selected value="%">Departing : All Airports</option>
                        <option value="CYQG">Windsor International Airport</option>
                        <option value="CYTZ">Billy Bishop Toronto City Airport</option>
                        <option value="CYXU">London International Airport</option>
                        <option value="CYYZ">Toronto Pearson International Airport</option>
                    </select>                
                </div>
                <div class="col-sm-3" align="center">
                    <select class="browser-default custom-select" name="arrival" aria-label="arrival airport">
                        <option class="hidden" selected value="%">Arriving : All Airports</option>
                        <option value="CYQG">Windsor International Airport</option>
                        <option value="CYTZ">Billy Bishop Toronto City Airport</option>
                        <option value="CYXU">London International Airport</option>
                        <option value="CYYZ">Toronto Pearson International Airport</option>
                    </select>                
                </div>
                <div class="col-sm-3" align="center">
                    <input type="date" class="form-control" id="date" placeholder="Date of Departure" name="date" aria-label="Date of departure" required>
                </div>
            </div>
            <div class = "row justify-content-center">
                <div class="col-md-3 col-sm-6" align="center">
                    <select name="model" id="model" class="browser-default custom-select" aria-label="Plane type">
                        <option class="hidden" selected value="%">Model : All</option>
                        <option value="1">Cessna 152</option>
                        <option value="2">Cessna 172</option>
                        <option value="3">Cessna 182</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6" align="center">
                    <select name="seat" id="seat" class="browser-default custom-select" aria-label="Seat choice">
                        <option class="hidden" selected value="">Seat Selection : All</option>
                        <?php
                            if($_SESSION['usertype'] == 2) echo '<option value="DELETE FROM `RESULTS` WHERE PLTRTG IS NOT NULL">Pilot in Command</option>';
                        ?>
                        <option value="DELETE FROM `RESULTS` WHERE S2RTG IS NOT NULL">Seat 2</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 align-self-center" align="end">
                    <div class="custom-control custom-switch">
                        <button class="btn btn-priary">
                            <div id="spinner" class="spinner-border spinner-border-sm" style="display:none"></div>
                        </button>
                        <button type="submit" class="btn btn-primary" aria-label="Search for flights" onclick="toggle_spinner()">
                            Search
                        </button>
                        <div id="spin_msg"></div>
                    </div>
                </div>
            </div>
        <?php
            if ($insertion)
            {
                echo "<div class = 'row justify-content-center' style='color:green'><h3>** Flight Joined **</h3></div>";
            }
            elseif ($guest)
            {
                echo "<div class = 'row justify-content-center' style='color:red'><p'><h3> - Please Sign In To Join Flights - </h3></p></div>";
            }
        ?>    
        </div>
    </form>
</div>
<br>
<div class="container">
    
<?php
    if (isset($_GET['date']))
    {
        if ($error)
            echo "<div class = 'row justify-content-center'><p><h4> - An Error Has Occured : Please Try Again - </h4></p></div>";
        elseif ($rows == 0)
            echo "<div class = 'row justify-content-center'><p><h4> - No Flights Found : Please Choose Another Date - </h4></p></div>";
        
        
        for ($i = 0; $i < $rows; $i++)
        {
            $available = 0;
            $new_result->data_seek($i);
            $fid = $new_result->fetch_assoc()['FID'];
            
            $new_result->data_seek($i);
            $dep = $new_result->fetch_assoc()['FM'];
            switch($dep)
            {
                case ('CYQG') : $dep = "Windsor Int'l";             break;
                case ('CYTZ') : $dep = "Toronto Billy Bishop";      break;
                case ('CYXU') : $dep = "London Int'l";              break;  
                case ('CYYZ') : $dep = "Toronto Pearson";           break;
            }
            
            $new_result->data_seek($i);
            $arv = $new_result->fetch_assoc()['TT'];
            switch($arv)
            {
                case ('CYQG') : $arv = "Windsor Int'l";             break;
                case ('CYTZ') : $arv = "Toronto Billy Bishop";      break;
                case ('CYXU') : $arv = "London Int'l";              break;  
                case ('CYYZ') : $arv = "Toronto Pearson";           break;
            }
            
            $new_result->data_seek($i);
            $date_time = $new_result->fetch_assoc()['DT'];
            $date = substr($date_time,0,11);
            
            $new_result->data_seek($i);
            $plane = $new_result->fetch_assoc()['PT'];
            
            $new_result->data_seek($i);
            $s1r = $new_result->fetch_assoc()['PLTRTG'];
            if ($s1r == "")
            {
                $available++;
                $s1 = "Open";
            }
            else
                $s1 = $s1r . " Stars";
            
            $new_result->data_seek($i);
            $s2r = $new_result->fetch_assoc()['S2RTG'];
            if ($s2r == "")
            {
                $available++;
                $s2 = "Open";
            }
            else
                $s2 = $s2r . " Stars";
            
            $new_result->data_seek($i);
            $s3r = $new_result->fetch_assoc()['S3RTG'];
            if ($s3r == "")
            {
                $available++;
                $s3 = "Open";
            }
            else
                $s3 = $s3r . " Stars"; 
                
            $new_result->data_seek($i);
            $s4r = $new_result->fetch_assoc()['S4RTG'];
            if ($s4r == "")
            {
                $available++;
                $s4 = "Open";
            }
            else
                $s4 = $s4r . " Stars";

echo <<<_END
            <div class = "row justify-content-start" style="border-style: ridge; border-radius: 5px; padding-top:10px; margin-bottom:10px;">
                <div class="col-md-4 col-sm-4 col-6">
                    <h5>$dep -> $arv</h5>
                    <h4><small>$date</small></h4>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <b>$available Seats Available</b>
                    <br>
                    <b>Model:</b> $plane
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <b>Seat 1</b>: $s1
                    <br>
                    <b>Seat 2</b>: $s2
                </div>
                <div class="col-md-2 col-sm-4 col-6">
_END;
echo <<<_END

_END;
            if ($plane == 'C-172' || $plane == 'C-182')
            {
                echo "<b>Seat 3:</b> $s3<br><b>Seat 4:</b> $s4";
            }
echo<<<_END
                </div>
                <div class="col-md-2 col-sm-4  col-6 align-self-center">
                    <form action="scheduled.php" method="get">
                    <div class="btn-group">
                        <select class="browser-default custom-select" name="passengers" id="passengers" aria-label="seat choice" required>
                            <option class="hidden" selected disabled>Seat</option>
_END;
                    if (($plane == 'C-172' || $plane == 'C-182') && $s4 == 'Open')
                    {
                        if ($user_type == 2)
                        {
echo <<<_END
                            <option value="INSERT INTO JOINS(SEAT, FID, PLN) VALUES (4, '$fid', (SELECT PLN FROM USERS WHERE UNAME='$user_name'));">Seat 4</option>;
_END;
                        }

                        elseif ($user_type == 3)
                        {
echo <<<_END
                            <option value="INSERT INTO JOINS(SEAT, FID, PSID) VALUES (4, '$fid', (SELECT PSID FROM USERS WHERE UNAME='$user_name'));">Seat 4</option>;
_END;

                        }
                    }
                    if (($plane == 'C-172' || $plane == 'C-182') && $s3 == 'Open')
                    {
                        if ($user_type == 2)
                        {
echo <<<_END
                            <option value="INSERT INTO JOINS(SEAT, FID, PLN) VALUES (3, '$fid', (SELECT PLN FROM USERS WHERE UNAME='$user_name'));">Seat 3</option>;
_END;
                        }

                        elseif ($user_type == 3)
                        {
echo <<<_END
                            <option value="INSERT INTO JOINS(SEAT, FID, PSID) VALUES (3, '$fid', (SELECT PSID FROM USERS WHERE UNAME='$user_name'));">Seat 3</option>;
_END;
                        }
                    }
                    if ($s2 == 'Open')
                    {
                        if ($user_type == 2)
                        {
echo <<<_END
                            <option value="INSERT INTO JOINS(SEAT, FID, PSID) VALUES (2, '$fid', (SELECT PSID FROM USERS WHERE UNAME='$user_name'));">Seat 2</option>;
_END;
                        }

                        elseif ($user_type == 3)
                        {
echo <<<_END
                            <option value="INSERT INTO JOINS(SEAT, FID, PSID) VALUES (2, '$fid', (SELECT PSID FROM USERS WHERE UNAME='$user_name'));">Seat 2</option>;
_END;
                        }
                        else
                            echo "<option value='guest'>Login to book!</option>";
                    }
                    if ($user_type == 2 && $s1 == 'Open') 
                    {
echo <<<_END
                        <option value="INSERT INTO MAKES(PLN, FID) VALUES ((SELECT PLN FROM USERS WHERE UNAME='$user_name'), '$fid');">Seat 1</option>;  
_END;

                    }

echo <<<_END
                        </select>
                        <input type="hidden" id="query" name="query" value="test">
                        <button type="submit" class="btn btn-primary" aria-label="Join Flight">Join</button>
                    </div>
                    </form>
                </div>
            </div>
_END;
        }        
    }
?>



</div>
</body>
</html>