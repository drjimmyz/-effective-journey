<?php
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $username = $password = $email = $key = $fail = "";
    $newline = '\n';
    $success = 0;
    $UN_fail = $PW_fail = $EM_fail = $KEY_fail = "";
    $red_border = array("pw"=>"", "un"=>"", "key"=>"", "em"=>"");
    $un_val = $key_val = $em_val = "";

    if (isset($_POST['username']))
    {
        $username = fix_string($_POST['username']);
        $un_val = $username;
    }
    if (isset($_POST['password']))
        $password = fix_string($_POST['password']);
    if (isset($_POST['email']))
    {
        $email = fix_string($_POST['email']);
        $em_val = $email;
    }
    if (isset($_POST['key']))
    {
        $key = fix_string($_POST['key']);
        $key_val = $key;
    }

    if (isset($_POST['submit']))
    {
        $fail = validate_username($username);
        $fail .= validate_password($password);
        $fail .= validate_email($email);
        $fail .= validate_key($key);

        if ($fail == "" && isset($_POST['username']) && isset($_POST['password']) &&
            isset($_POST['email']) && isset($_POST['key']))
        {
            $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

            $un_temp = mysql_entities_fix_string($connection, $_POST['username']);
            $pw_temp = mysql_entities_fix_string($connection, $_POST['password']);
            $em_temp = mysql_entities_fix_string($connection, $_POST['email']);
            $key_temp = mysql_entities_fix_string($connection, $_POST['key']);

            $query = "SELECT * FROM users WHERE username='$un_temp'";
            $query2 = "SELECT * FROM users WHERE email='$em_temp'";
            $query3 = "SELECT used FROM user_keys WHERE user_key='$key_temp'";
            $result = $connection->query($query);
            $result2 = $connection->query($query2);
            $result3 = $connection->query($query3);

            if (!$result) die($connection->error);
            elseif ($result->num_rows)
                {
                    $UN_fail = "<div class='error_holder'>Username already taken.</div>";
                    $red_border["un"] = "style='border-color: red;'";
                    $result->close();
                }

            elseif (!$result2) die($connection->error);
            elseif ($result2->num_rows)
                {
                    $EM_fail = "<div class='error_holder'>The entered e-mail is already registred.</div>";
                    $red_border["em"] = "style='border-color: red;'";
                    $result2->close();
                }

            elseif (!$result3) die($connection->error);
            elseif (!$result3->num_rows)
                {
                    $KEY_fail = "<div class='error_holder'>You have entered an invalid key.</div>";
                    $red_border["key"] = "style='border-color: red;'";
                    $result3->close();
                }

            else
            {
                $row = $result3->fetch_array(MYSQLI_ASSOC);
                $result->close();

                if ($row['used'] == 1)
                    $KEY_fail = "<div class='error_holder'>The entered key has already been used.</div>";

                elseif($row['used'] == 0)
                {

                    $salt1 = "^´fdg";
                    $salt2 = "k9p.<";
                    $token = hash('ripemd128', "$salt1$pw_temp$salt2");

                    $query = "UPDATE user_keys SET used='1', user_used='$un_temp' WHERE user_key='$key_temp'";
                    $query2 = "INSERT INTO users (username, password, email)
                               VALUES('$un_temp', '$token', '$em_temp')";




                    $result = $connection->query($query);
                    if (!$result) die($connection->error);

                    $result2 = $connection->query($query2);
                    if (!$result2) die($connection->error);

                    $success = 1;

                }
            }

        }
    }

    // Min length 1, max length 16, only a-z, A-Z, 0-9, - and _.
    function validate_username($field)
    {
        if ($field == "") return "No username was entered<br>";
        else if (strlen($field) > 16)
            return "Username max length is 16 characters.";
        else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
            return "Only letters, numbers, - and _ in usernames.";
        else return "";
    }

    // Length 10-16 characters, only a-z, A-Z, 0-9, - and _.
    function validate_password($field)
    {
        if ($field == "") return "No password was entered.";
        else if (strlen($field) < 10) return "Passwords must be at least 10 characters.";
        else if (strlen($field) > 16) return "Password max length is 16 characters.";
        else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
            return "Only letters, numbers, - and _ in passwords.";
        else return "";
    }

    // Length 10-64 characters, not invalid.
    function validate_email($field)
    {
        if ($field == "") return "No e-mail was entered.";
        else if (strlen($field) < 5) return "E-mail must be at least 5 characters.";
        else if (strlen($field) > 64) return "E-mail max length is 64 characters.";
        else if (!filter_var($field, FILTER_VALIDATE_EMAIL))
            return "Invalid e-mail.";
        else return "";
    }

    // Length 16, not invalid.
    function validate_key($field)
    {
        if ($field == "")
            return "No key was entered.";
        else if (strlen($field) != 16)
            return "You have entered an invalid key.";
        else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
            return "You have entered an invalid key.";
        else return "";
    }

    function error_div($string)
    {
        return "<div class='error_holder'>" . $string . "</div>";
    }

    function fix_string($string)
    {
        if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return htmlentities ($string);
    }


    if ($success == 0)
        echo <<<_END

<html>
    <head>
    <link rel='stylesheet' type='text/css' href='css/register.css'>
    <title> Register </title>

    <script>
        function validate(form)
        {
            fail = validateUsername(form.username.value);
            fail += validatePassword(form.password.value);

            if (fail == "") return true;
            else { window.alert(fail); return false;}
        }


        function validateUsername(field)
        {
            if (/[^a-zA-Z0-9_-]/.test(field))
                return "Only a-z, A-Z, 0-9, - and _ allowed in usernames.$newline";
            else return "";
        }

        function validatePassword(field)
        {
            if (field.length < 10)
                return "Passwords must be at least 10 characters.";
            else if (/[^a-zA-Z0-9_-]/.test(field))
                return "Only a-z, A-Z, 0-9, - and _ allowed in passwords.$newline";
            else return "";
        }
    </script>

    </head>

<body>
$fail
<div id='form_holder'>
    <form method='post' action='register.php' onsubmit='return validate(this)'>
    <input type='text' name='username' value='$un_val' $red_border[un] placeholder='Username' maxlength='16' required autofocus>
    $UN_fail
    <input type='password' name='password' $red_border[pw] placeholder='Password' maxlength='16' required>
    $PW_fail
    <input type='text' name='email' value='$em_val' $red_border[em] placeholder='E-mail' maxlength='64' required>
    $EM_fail
    <input type='key' name='key' value='$key_val' $red_border[key] placeholder='Key' maxlength='16' required>
    $KEY_fail
    <input type='submit' name='submit' value='Register'>
    </form>

</div>
</body>

</html>

_END;

    elseif ($success == 1)
    {
        echo <<<_END
<html>
    <head>
    <link rel='stylesheet' type='text/css' href='css/register.css'>
    <title> Register </title>
    </head>

    <body>

    <div id='form_holder'>
    <p>The registration was successful. You can now <a href='login.php'>login</a></p>
    </div>


    </body>
</html>
_END;
    }


?>