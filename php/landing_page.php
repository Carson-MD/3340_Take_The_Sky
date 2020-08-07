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
    <title>Take The Sky - Home</title>
    <link rel="icon" href="../images/logo_icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset = "utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>  
    <style>
        .scheduled {
            background-image: url('http://dickiec.myweb.cs.uwindsor.ca/60334/project/images/bridge.png');
            background-position: center;
            background-size: contain;
        }
        .on-demand {
            background-image: url('http://dickiec.myweb.cs.uwindsor.ca/60334/project/images/skyline.png');
            background-position: center;
            background-size: contain;
        }
        .main-link {
            background-color: rgba(91,22,139,0.80);
            padding: 10px;
            padding-right: 20px;
            padding-left: 20px;
            color: white;
        }
        .main-link-2 {
            background-color: rgba(139,41,33,0.80);
            padding: 10px;
            padding-right: 20px;
            padding-left: 20px;
            color: white;
        }
    </style>
</head>
<body>
    <?php
        session_start();
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
    
    <div class="container">
        <div class="row scheduled justify-content-around">
            <div class="col-md-4" align="center">
                <img src="../images/scheduled.png" alt="Premium 9 Seater Plane " style="width:50%">
            </div>
            <div class="col-md-4 align-self-center">
                <a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/scheduled.php" aria-label="Browse scheduled flights">
                    <div class="main-link-2" onclick=""https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/scheduled.php"">
                        <h1 style="border-bottom: 1px solid">Scheduled</h1>
                        <p><h5><small>Endless possibilities - right at your fingertips!</small></h5></p>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <br>
        </div>
        <div class="row on-demand justify-content-around">
            <div class="col-md-4 align-self-center">
                <a class="nav-link" href="https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/scheduled.php" aria-label="Book a new route">
                    <div class="main-link" onclick=""https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/on_demand.php"">
                        <h1 style="border-bottom: 1px solid"> On Demand</h1>
                        <p><h5><small> Can't find the flight you're looking for? Book your own! </small></h5></p>
                    </div>
                </a>
            </div>
            <div class="col-md-4 align-self-center" align="center">
                <img src="../images/on_demand.png" alt="Premium 3 Seater Plane " style="width:50%">
            </div>
        </div>
    </div>
</body>
</html>