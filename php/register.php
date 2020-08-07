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
    <title>Take The Sky - Register</title>
    <link rel="icon" href="../images/logo_icon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset = "utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
    <script src="../js/validation.js"></script>
</head>
<body>
    
<?php

require_once 'authentication.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_POST['full_name']) &&
    isset($_POST['user_name']) &&
    isset($_POST['password'])  &&
    isset($_POST['address'])   &&
    isset($_POST['phone'])     &&
    isset($_POST['payment'])   &&
    isset($_POST['age']))
  {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone   = $conn->real_escape_string($_POST['phone']);
    $payment = $conn->real_escape_string($_POST['payment']);
    $age = (int)($conn->real_escape_string($_POST['age']));
    $level = 3;

    $pwd = $conn->real_escape_string($_POST['password']);
    $pwd_salt = "hqb%_t" . $pwd . "cg*l";
    $password = hash('ripemd128', $pwd_salt);
  
    $stmt = $conn->prepare("INSERT INTO `PASSENGER`(`NAME`, `ADDRESS`, `PHONE`, `PAYMENT`, `AGE`) VALUES (?, ?, ?, ?, ?);");
    $stmt->bind_param("ssssi", $full_name, $address, $phone, $payment, $age);
    $result = $stmt->execute();
    $stmt->close();
    
    if (!$result) echo "First insert failed: " .
      $conn->error . "<br><br>";
    else
      $a = true; 
    
    $stmt = $conn->prepare("INSERT INTO `USERS`(`UNAME`, `PASSWORD`, `LEVEL`, `PSID`) VALUES (?, ?, ?, (SELECT MAX(PSID) FROM PASSENGER));");
    $stmt->bind_param("ssi", $user_name, $password, $level);
    $result2 = $stmt->execute();
    $stmt->close();
  	if (!$result2) echo "Second insert failed: " .
      $conn->error . "<br><br>";
    else
      $b = true;
  }
if (isset($_POST['pilot_name'])     &&
    isset($_POST['license_number']) &&
    isset($_POST['flight_hours'])   &&
    isset($_POST['pilot_un'])       &&
    isset($_POST['pilot_address'])  &&
    isset($_POST['pilot_phone'])    &&
    isset($_POST['license_type'])   &&
    isset($_POST['pilot_payment'])  &&
    isset($_POST['pilot_pw']))
  {
    $pilot_name = $conn->real_escape_string($_POST['pilot_name']);
    $license_number = $conn->real_escape_string($_POST['license_number']);
    $license_type = $conn->real_escape_string($_POST['license_type']);
    $flight_hours = (int)($conn->real_escape_string($_POST['flight_hours']));
    $pilot_un = $conn->real_escape_string($_POST['pilot_un']);
    $pilot_address = $conn->real_escape_string($_POST['pilot_address']);
    $pilot_phone   = $conn->real_escape_string($_POST['pilot_phone']);
    $pilot_payment = $conn->real_escape_string($_POST['pilot_payment']);
    $level = 2;
    
    $pwd = $conn->real_escape_string($_POST['pilot_pw']);
    $pwd_salt = "hqb%_t" . $pwd . "cg*l";
    $password = hash('ripemd128', $pwd_salt);

    $stmt = $conn->prepare("INSERT INTO `PILOT`(`PLN`, `NAME`, `LTYPE`, `FHOURS`, `ADDRESS`, `PHONE`, `PAYMENT`) VALUES (?, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("sssisss", $license_number, $pilot_name, $license_type, $flight_hours, $pilot_address, $pilot_phone, $pilot_payment);
    $result = $stmt->execute();
    $stmt->close();
    
    if (!$result) echo "First insert failed: " .
      $conn->error . "<br><br>";
    else
        $a = true;
    $stmt = $conn->prepare("INSERT INTO `USERS`(`UNAME`, `PASSWORD`, `LEVEL`, `PLN`) VALUES (?, ?, ?, ?);");
    $stmt->bind_param("ssis", $pilot_un, $password, $level, $license_number);
    $result2 = $stmt->execute();
    $stmt->close();
    
  	if (!$result2) echo "Second insert failed: " .
      $conn->error . "<br><br>";
    else
        $b = true;
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
            <div class="col-md-3 register-left">
                <img src="../images/prop_plane.png" alt="Miniature propeller plane in flight"/>
                <h3>Welcome</h3>
                <p>Fly with us - the sky is the limit!</p>
                <?php
                    if ($a && $b)
                        echo "<p class='font-weight-bold'>Account Created!<br>Please Log In.</p>";
                ?>
                <input type="submit" name="" value="Login" onclick="location.href = 'https://dickiec.myweb.cs.uwindsor.ca/60334/project/php/login.php';"/><br/>
            </div>
            <div class="col-md-9 register-right">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Passenger</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Pilot</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form action="register.php" method="post" onsubmit="reg_validate_matching()">
                        <h3 class="register-heading">Register as a Passenger</h3>
                        <div class="row register-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Full Name *" name="full_name" value="" required aria-label="Full name"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="User Name *" id="user_name" name="user_name" onfocusout="reg_show_hint()" required aria-label="User name"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" minlength="8" placeholder="Password *" name="password" id="password" value="" required aria-label="Password"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" minlength="8" placeholder="Confirm Password *" name = "password2" id="password2" value="" required aria-label="Confirm password"/>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox" >
                                        <input type="checkbox" class="custom-control-input" id="customCheck" name="human" id="human" required>
                                        <label class="custom-control-label" for="customCheck">I am not a robot</label>
                                    </div>
                                    <p><span id="text_hint" class="text-warning"></span></p>
                                    <p><span id="pw_hint" class="text-danger"></span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Address *" name="address" value="" required aria-label="Full address"/>
                                </div>
                                <div class="form-group">
                                    <input type="tel" pattern="[0-9]{3}-*[0-9]{3}-*[0-9]{4}" maxlength="12" class="form-control" placeholder="Phone * (123-456-7890)" name="phone" value="" required aria-label="Phone number"/>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="payment" required aria-label="Choose payment method">
                                        <option class="hidden" selected disabled>Payment Method</option>
                                        <option value="Visa">Visa</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="American">American Express</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="number" min=18 max=100 name="age" class="form-control" placeholder="Your Age *" required aria-label="Your age"/>
                                </div>
                                <input type="submit" class="btnRegister"  value="Register" aria-label="Register as a passenger"/>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="register.php" method="post" onsubmit="reg_validate_matching_2()">
                        <h3  class="register-heading">Register as a Pilot</h3>
                        <div class="row register-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Full Name *" name="pilot_name" value="" required aria-label="Full name"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="License # *" name="license_number" value="" required aria-label="Pilot license number"/>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="# Flight Hours *" name="flight_hours" value="" required aria-label="Number of flight hours"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="User Name *" name="pilot_un" id="pilot_un" onfocusout="reg_show_hint_2()" required aria-label="User name"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" minlength="8" placeholder="Password *" name="pilot_pw" id="pilot_pw" value="" required aria-label="Password"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" minlength="8" placeholder="Confirm Password *" name="pilot_pwc" id="pilot_pwc" value="" required aria-label="Confirm pasword"/>
                                </div>
                                <p><span id="text_hint_2" class="text-warning"></span></p>
                                <p><span id="pw_hint_2" class="text-danger"></span></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Address *" name="pilot_address" value="" required aria-label="Full address"/>
                                </div>
                                <div class="form-group">
                                    <input type="tel" pattern="[0-9]{3}-*[0-9]{3}-*[0-9]{4}" maxlength="12" class="form-control" placeholder="Phone * (123-456-7890)" name="pilot_phone" value="" required aria-label="Phone number"/>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="license_type" required aria-label="Pilot license type">
                                        <option class="hidden"  selected disabled>License Type *</option>
                                        <option value="PPL">PPL</option>
                                        <option value="CPL">CPL</option>
                                        <option value="ALTP">ALTP</option>
                                        <option value="IMCPL">IMCPL</option>
                                        <option value="ISCPL">ISCPL</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="pilot_payment" required aria-label="Choose payment method">
                                        <option class="hidden"  selected disabled>Payment Method *</option>
                                        <option value="Visa">Visa</option>
                                        <option value="Mastercard">Mastercard</option>
                                        <option value="American Express">American Express</option>
                                    </select>                                
                                </div>
                                <input type="submit" class="btnRegister"  value="Register" aria-label="Register as a pilot"/>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</body>
</html>