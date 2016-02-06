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
        $target_file = hash('ripemd128', $_FILES['fileToUpload']['tmp_name']) . "." . $imageFileType;
        $target_path = $target_dir . $target_file;

        if (file_exists($target_path))
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

            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path))
            {
                $fail_msg = 'File uploaded.';

                $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
                $ti_temp = mysql_entities_fix_string($connection, $title);
                $query = "INSERT INTO user_images(user_id, title, path)
                          VALUES ('$user_id', '$ti_temp', '$target_path')";
                $result = $connection->query($query);
                if (!$result) die($connection->error);
                $connection->close();

                makeThumbnail($target_dir, $target_file);
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

    function makeThumbnail($updir, $img)
    {
        $thumbnail_width = 150;
        $thumbnail_height = 150;
        $thumb_beforeword = "thumb";
        $arr_image_details = getimagesize("$updir" . "$img");
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * $new_width / $original_width);
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * $new_height / $original_height);
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);
        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "imagecreatefromgif";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "imagecreatefromjpeg";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "imagecreatefrompng";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom("$updir" . "$img");
            $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $imgt($new_image, "$updir" . "$thumb_beforeword" . "$img");
        }
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