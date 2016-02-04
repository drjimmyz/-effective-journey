<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    if ($user_class != 'admin')
    {
        header('Location: index.php');
        exit();
    }
    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    $no_image = true;

    if(isset($_GET['img_id']))
    {
        $img_id = mysql_entities_fix_string($connection, $_GET['img_id']);
        $query = "SELECT * FROM user_images WHERE img_id='$img_id' AND user_id='$user_id'";

        $result = $connection->query($query);
        if (!$result) die($connection->error);

        if ($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $img_path = $row['path'];
            $img_title = $row['title'];
            $no_image = false;
        }
    }

    if ($no_image)
    {
        $query = "SELECT * FROM user_images WHERE user_id='$user_id' ORDER BY img_id LIMIT 1";
        $result = $connection->query($query);
        if (!$result) die($connection->error);

        if ($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            header("Location: pictures.php?img_id=" . $row['img_id'] . "#main");
            exit();
        }
    }

?>

<html>
<head>
    <title>Site</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
    function resizeIframe(obj)
    {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        $("#img_title > h2").text(titles[current_img]);
    }

    function updateIframe(dir)
    {
        current_img += dir;
        if (current_img < 0) current_img = paths.length-1;
        if (current_img > paths.length-1) current_img = 0;
        $("#img_display").attr("src", "show_img.php?src=" + paths[current_img]);
    }

    var img_ids = [], titles = [], paths = [];
    var current_img;

    <?php

    $query = "SELECT * FROM user_images WHERE user_id='$user_id' ORDER BY img_id";
    $result = $connection->query($query);
    if (!$result) die($connection->error);

    $rows = $result->num_rows;

    for ($j = 0; $j < $rows; ++$j)
    {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_ASSOC);

        echo "img_ids.push(\"" . $row['img_id'] ."\");";
        echo "titles.push(\"" . $row['title'] ."\");";
        echo "paths.push(\"" . $row['path'] ."\");";

    }

    echo "for(var i = 0; i < img_ids.length; ++i) {";
    echo "if (img_ids[i] == $img_id) current_img = i;";
    echo "}";

    ?>

    </script>
    <link rel='stylesheet' type='text/css' href='css/main.css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>

<body>
<?php include_once 'private/top_and_menu.php'; ?>
<div id='main'>

<?php
    if (!$no_image)
    {
        echo "<iframe id='img_display' width='100%' src='show_img.php?src=$img_path' frameborder='0' scrolling='no' onload='resizeIframe(this)'></iframe>";

        $next = 'null';
        $prev = 'null';

        $query = "SELECT * FROM user_images WHERE user_id='$user_id' AND img_id>'$img_id' ORDER BY img_id LIMIT 1";
        $result = $connection->query($query);
        if ($result->num_rows)
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $next = "show_img.php?src=" . $row['path'];
            }

        $query = "SELECT * FROM user_images WHERE user_id='$user_id' AND img_id<'$img_id' ORDER BY img_id DESC LIMIT 1";
        $result = $connection->query($query);
        if ($result->num_rows)
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $prev = "show_img.php?src=" . $row['path'];
            }
?>

<div id='img_title'>
<h2>
</h2>
</div>

<?php
        echo "<div id='img_buttons'>";
        echo "<button class='stnd-button-large' onclick='updateIframe(-1)'>Prev image</button>";
        echo "<button class='stnd-button-large' onclick='updateIframe(1)'>Next image</button>";
        echo "</div>";
    }
    else echo "<p>No images uploaded yet.</p>";

?>

</div>

<div id='bottom'>
</div>
<script src="js/dropdown-menu.js"></script>
</body>
</html>