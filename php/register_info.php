<?php


require_once 'authentication.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) 
    die($conn->connect_error);

$user_name = $_REQUEST['user_name'];
$hint = "Username not unique";

if (isset($_REQUEST['user_name']))
{
    $un_temp = mysql_entities_fix_string($conn, $user_name);
    $query = "SELECT * FROM `USERS` WHERE `UNAME` = '$un_temp'";
    $result = $conn->query($query);
    $count = $result->num_rows;
    if (!$result) 
        die($conn->error);

    elseif ($count == 0)
    {
            $hint = "Your username is valid";
    }    
    else
        die("Username already in use");
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