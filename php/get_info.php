<?php

require_once 'authentication.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) 
    die($conn->connect_error);

$user_name = $_REQUEST['user_name'];
$pwd = $_REQUEST['password'];
$hint = "username and password do not match our records or do not exist";

if (isset($_REQUEST['user_name']) && isset($_REQUEST['password']))
{
    $un_temp = mysql_entities_fix_string($conn, $user_name);
    $pw_temp = mysql_entities_fix_string($conn, $pwd);
    $query = "SELECT * FROM `USERS` WHERE `UNAME` = '$un_temp'";
    $result = $conn->query($query);
    
    if (!$result) 
        die($conn->error);
    
    elseif ($result->num_rows)
    {
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();
        $pwd_salt = "hqb%_t" . $pw_temp . "cg*l";
        $password = hash('ripemd128', $pwd_salt);
        
        if ($password == $row[2])
        {
            // Set the session variables
            session_start();
            $_SESSION['username'] = $un_temp;
            $_SESSION['usertype'] = $row[3];
            
            
            if ($row[3] == 2)                       // Pilot
                $_SESSION['userid'] = $row[4];
            elseif ($row[3] == 3)                    // Passenger
                $_SESSION['userid'] = $row[5];
            
            $hint = "Login Accepted";
        }
        else
            die("Invalid username, password combination");
    }    
    else
        die("Invalid username, password combination");
}

$conn->close();
echo $hint;

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