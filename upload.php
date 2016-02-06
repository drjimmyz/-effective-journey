<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $fail_msg = '';


    if(isset($_POST['submit']) && isset($_POST['title']))
    {

        $target_dir = 'uploads/';
        $uploadOK = 1;
        $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
        $check !== false ? $uploadOK = 1 : $uploadOK = 0;

        $title = fix_string($_POST['title']);
        validate_title($title) == '' ? $uploadOK = 1 : $uploadOK = 0;
    

        if (file_exists($target_file))
        {
            $uploadOK = 0;
            $fail_msg = 'Already exists.';
        }

        if ($_FILES['fileToUpload']['size'] > 10000000)
        {
            $uploadOK = 0;
            $fail_msg = 'Too big.';
        }

        if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg')
        {
            $uploadOK = 0;
            $fail_msg = 'Not jpg, png or jpeg.';
        }

        if ($uploadOK == 0)
        {

        }
        else
        {

            $target_file = $target_dir . hash('ripemd128', $_FILES['fileToUpload']['tmp_name']) . "." . $imageFileType;
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file))
            {
                $fail_msg = 'File uploaded.';

                $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
                $ti_temp = mysql_entities_fix_string($connection, $title);
                $query = "INSERT INTO user_images(user_id, title, path)
                          VALUES ('$user_id', '$ti_temp', '$target_file')";
                $result = $connection->query($query);
                if (!$result) die($connection->error);
                $connection->close();
            }
            else
            {
                $fail_msg = 'There was an error.';
            }
        }


    }

    function validate_title($field)
    {
        if (strlen($field) > 64) return "Stop it.";
        elseif (!preg_match("/^[a-zA-Z0-9 ']+$/", $field)) return "Invalid title. Currently only accepts a-z, A-Z and 0-9.";
        else return "";
    }

    function fix_string($string)
    {
        if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return htmlentities ($string);
    }

?>

<html>
<head>
    <title>Site</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <link rel='stylesheet' type='text/css' href='css/main.css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>

<body>
<?php include_once 'private/top_and_menu.php'; ?>
<div id='main'>

    <div id='form_holder'>
    <form method='post' action='upload.php' enctype='multipart/form-data'>
    <input type='text' name='title' placeholder='Image title' maxlength='64' requried>
    <input type='file' id='img_upload' name='fileToUpload' required>
    <input class='stnd-button' type='submit' name='submit'>
    </form>
    </div>

    <?php echo $fail_msg; ?>

</div>
<div id='bottom'>
</div>
<script src="js/dropdown-menu.js"></script>
</body>
</html>