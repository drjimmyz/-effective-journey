<?php
    require_once 'private/sqldetails.php';
    $enter_pw = '';
    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

    if ($connection->connect_error) die ($connection->connect_error);

    if (isset($_POST['username']) && isset($_POST['password']))
    {
        $un_temp = mysql_entities_fix_string($connection, $_POST['username']);
        $pw_temp = mysql_entities_fix_string($connection, $_POST['password']);

        $query = "SELECT * FROM users WHERE username='$un_temp'";
        $result = $connection->query($query);
        if (!$result) die($connection->error);
        elseif ($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $result->close();

            $salt1 = "^´fdg";
            $salt2 = "k9p.<";
            $token = hash('ripemd128', "$salt1$pw_temp$salt2");

            if ($token == $row['password'])
            {
                session_start();
                $_SESSION['username'] = $un_temp;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_class'] = $row['user_class'];
                $_SESSION['check'] = hash('ripemd128',
                    $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);



                header('Location: index.php');
            }
            else $enter_pw = "The username or password is wrong.<br>Please try again.";
        } else $enter_pw = "The username or password is wrong.<br>Please try again.";
    }


    function mysql_entities_fix_string($connection, $string)
    {
        return htmlentities(mysql_fix_string($connection, $string));
    }

    function mysql_fix_string($connection, $string)
    {
        if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return $connection->real_escape_string($string);
    }

?>

<html>
<head>
<title>Login</title>
<link rel='stylesheet' type='text/css' href='css/login.css'>
</head>
<body>
<div id='login_middle'>
<div id='login_content'>
<div id='form_holder'>
<form method='post' action='login.php'>
<input type='text' name='username' placeholder='Username' required autofocus>
<input type='password' name='password' placeholder='Password' required>
<input type='submit' value='Login'>
</form>
</div>
<div id='message_placeholder'>
<a href='register.php'>Register</a><br><br>
<?php echo $enter_pw; ?>
</div>
</div>
</div>
</body>
</html>