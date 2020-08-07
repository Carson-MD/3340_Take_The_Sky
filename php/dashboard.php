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
    <title>Take The Sky - Dashboard</title>
    <link rel="icon" href="../images/logo_icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset = "utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<?php
    session_start();

    if($_SESSION['usertype'] == 1)
    {
        require_once 'authentication.php';
        require_once 'sanitize.php';
        
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) 
            die($conn->connect_error);
/*************************** DISPLAY CHART ***************************/        
        $query = "SELECT F.AID, COUNT(*)as COUNT
              FROM FLIGHT FL, FROMM F
              WHERE FL.FID = F.FID
              GROUP BY AID
              ORDER BY AID;"
        ;
    
        $result = $conn->query($query);
        if (!$result) 
            die($conn->error);
        $rows = $result->num_rows;

        $query2 = "SELECT T.AID, COUNT(*) as COUNT
                  FROM FLIGHT FL, TOO T
                  WHERE FL.FID = T.FID
                  GROUP BY AID
                  ORDER BY AID;"
        ;
                
        $result2 = $conn->query($query2); 
        if (!$result2) 
            die($conn->error);

echo <<<_END
<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Airport', 'Outgoing', 'Incoming', 'Cancelled'],
_END;
        for ($i = 0; $i < $rows; $i++)
        {
            $result->data_seek($i);
            $code = $result->fetch_assoc()['AID'];
            
            switch($code)
            {
                case "CYQG" : $airport = "'Windsor Int'";    break;
                case "CYTZ" : $airport = "'Toronto City'";    break;
                case "CYXU" : $airport = "'London Int'";     break;
                case "CYYZ" : $airport = "'Toronto Pearson'"; break;
            }
            
            $result->data_seek($i);
            $n_dep = $result->fetch_assoc()['COUNT'];
            $result2->data_seek($i);
            $n_arr = $result2->fetch_assoc()['COUNT'];
            
            $cancel = ($i*4)/2 + 7;
            
            echo "[$airport, $n_dep, $n_arr, $cancel]";
            if ($i < $rows - 1)
                echo ",";
            else
                echo "]);";
        }
echo <<<_END
        var options = {
          chart: {
            title: 'Total Flights By City',
            subtitle: 'Outgoing, Incoming, and Cancelled',
          },
          bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>
_END;
/*************************** DELETE ROUTES ***************************/        
        if (isset($_GET['fid']))
        {
            $fid = mysql_entities_fix_string($conn, $_GET['fid']);
            
            $query = "DELETE FROM MAKES WHERE FID = $fid; 
                      DELETE FROM USINGG WHERE FID = $fid; 
                      DELETE FROM FROMM WHERE FID = $fid; 
                      DELETE FROM TOO WHERE FID = $fid;  
                      DELETE FROM JOINS WHERE FID = $fid; 
                      DELETE FROM FLIGHT WHERE FID = $fid;"
            ;
            $result = $conn->multi_query($query);
            if (!$result) 
                die($conn->error);
            else
                $deleted = true;
        }
/*************************** SEARCH ROUTES ***************************/
        if (isset($_GET['departure']) ||
            isset($_GET['arrival'])   ||
            isset($_GET['date']))
        {
            $departure = $_GET['departure'];
            $arrival = $_GET['arrival'];
            $date = $_GET['date'];
            if ($date == "")
                $date_query = "";
            else
                $date_query = " AND FL.FDATE = '$date'";
                
            
            if ($departure == "any")
                $dep_query = "";
            else
                $dep_query = " AND F.AID = '$departure'";
                
            if ($arrival == "any")
                $arv_query = "";
            else $arv_query = " AND T.AID = '$arrival'";
            
    
            $query = "SELECT FL.FDATE as DATE, FL.FID, F.AID AS DEP, T.AID AS ARV
                      FROM FLIGHT FL, FROMM F, TOO T
                      WHERE FL.FID = F.FID AND F.FID = T.FID" . $date_query . $arv_query . $dep_query . ";";

            $result = $conn->query($query);
            if (!$result) 
                die($conn->error);
            $rows = $result->num_rows;
        }
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
    
    <div class="container register">
        <div class="row">
            <div class="col-md-6">
                <iframe style="max-width: 100%" width="489" height="303" seamless frameborder="0" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vQ7WVFpWcXFhvEhjOBvVdDtPquK9xM7OwQthe2QBR6L_5f5aNm9tRaHjRyNBJfJRKI5lcCKbc-rL6l_/pubchart?oid=228766806&amp;format=interactive"></iframe>
            </div>
            
            <div class="col-md-6">
                <iframe style="max-width: 100%" width="489" height="303" seamless frameborder="0" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vQ7WVFpWcXFhvEhjOBvVdDtPquK9xM7OwQthe2QBR6L_5f5aNm9tRaHjRyNBJfJRKI5lcCKbc-rL6l_/pubchart?oid=965435833&amp;format=interactive"></iframe>           
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div id="barchart_material" style="width: 95%;"></div><br>
            </div>
            <div class="col-md-4">
                <form action="dashboard.php" method="get">
                    <div class="form-group">
                        <h4 style="color:white">Search Flights By Date</h4>
                            <select class="browser-default custom-select" name="departure" aria-label="Select departure airport" value="test">
                                <option class="hidden" selected value="any">Departing *</option>
                                <option value="CYQG">Windsor International Airport</option>
                                <option value="CYTZ">Billy Bishop Toronto City Airport</option>
                                <option value="CYXU">London International Airport</option>
                                <option value="CYYZ">Toronto Pearson International Airport</option>
                            </select>
                            <select class="browser-default custom-select" name="arrival" aria-label="Select arrival airport" value="">
                                <option class="hidden" selected value="any">Arriving *</option>
                                <option value="CYQG">Windsor International Airport</option>
                                <option value="CYTZ">Billy Bishop Toronto City Airport</option>
                                <option value="CYXU">London International Airport</option>
                                <option value="CYYZ">Toronto Pearson International Airport</option>
                            </select>
                        <input type="date" class="form-control" id="date" name="date" aria-label="Departure date">
                        <button type="submit" class="btn btn-primary" arial-label="Search for flights">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <form action="dashboard.php" method="get">
                    <div class="form-group">
                        <h4 style="color:white">Delete Flights</h4>
                        <input type="text" class="form-control" placeholder="Flight ID *" name="fid" value="" required aria-label="Flight number"/>
                        <button type="submit" class="btn btn-primary" aria-label="Delete flight corresponding with flight ID">Delete Route</button>
                    </div>
                    <?php
                        if ($deleted) 
                            echo "<h5 style='color:white'>Flight $fid Deleted</h5>";
                    ?>
                </form>
            </div>
        </div>
<?php
if (isset($_GET['departure']) ||
     isset($_GET['arrival'])   ||
     isset($_GET['date']))
{
echo <<<_END
<table class="table table-hover" style="color:white">
    <thead>
          <tr>
                <th>Date</th>
                <th>Flight ID</th>
                <th>Departing</th>
                <th>Arriving</th>
          </tr>
    </thead>
    <tbody>
_END;
                
    for ($i = 0; $i < $rows; $i++)
    {
        $result->data_seek($i);
        $table_datetime = $result->fetch_assoc()['DATE'];
        $table_date = substr($table_datetime,0,11);
        $result->data_seek($i);
        $table_fid = $result->fetch_assoc()['FID'];
        $result->data_seek($i);
        $table_dep = $result->fetch_assoc()['DEP'];
        $result->data_seek($i);
        $table_arv = $result->fetch_assoc()['ARV'];

        echo "<tr><td>$table_date</td><td>$table_fid</td><td>$table_dep</td><td>$table_arv</td></tr>";
    }
}
echo <<<_END
    </tbody>
</table>
_END;
?>
    </div>
</body>
</html>