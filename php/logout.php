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
    <title>Take The Sky - Goodbye</title>
    <link rel="icon" href="../images/logo_icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset = "utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>  
    <style>
    .ft {
        position: absolute;
        top: 35%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    </style>
</head>
<body>
    <?php
        session_start();
        session_unset();
        session_destroy();
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
    
    <div class="container fairwell">
        <img src="../images/tropical.jpg" alt="Tropical sunset " style="width:100%">
        <span class="ft"><h1>See you soon!</h1></span>  
</body>
</html>