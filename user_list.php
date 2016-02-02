<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $now = new DateTime(date("Y-m-d H:i:s"));

    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    $query = "SELECT * FROM users";
?>

<html>
<head>
    <title>Site</title>
    <script src="jquery-1.12.0.min.js"></script>
    <link rel='stylesheet' type='text/css' href='css/main.css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>

<body>

    <div id='user_display'>
    You are logged in as: <?php echo $username; ?><br><a href='logout.php'>Log out</a>
    </div>

    <div id='top'>
    <img src='pictures/logo.png' id='logo'>

    <ul id='menu'>
        <li><a href='index.php'>Home</a></li>
        <?php echo "<li><a href='movie_list.php?user_id=$user_id'>Movie list</a></li>"; ?>
        
        <li><a href='user_list.php'>User list</a></li>
    </ul>
    </div>

    <div id='main'>
    <table id='video_ratings'>
        <tr>
            <th>User</th>
            <th>Joined</th>
            <th>Last seen</th>
            <th></th>
        </tr>
    <?php
        $result = $connection->query($query);
        if (!$result) die($connection->error);

        $rows = $result->num_rows;

        for ($j = 0; $j < $rows ; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if ($j % 2 == 0) echo "<tr class='even'>";
            else echo '<tr>';

            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . time_dif($row['created'], $now) . '</td>';
            echo '<td>' . time_dif($row['last_seen'], $now) . '</td>';
            echo "<td><a class='stnd-button' href='movie_list.php?user_id=";
            echo $row['user_id'] . "'>Movie list</a></td></tr>";
            
        }

        function time_dif($latest, $now)
        {

            $last_seen = new DateTime($latest);
            $interval = $last_seen->diff($now);

            if ($interval->days == 0)
            {
                if ($interval->h != 0) return $interval->h . ($interval->h == 1 ? " hour ago" : " hours ago");
                elseif ($interval->i > 10) return $interval->i . " minutes ago";
                else return "Just now";
            }
            return $interval->days . ($interval->days == 1 ? " day ago" : " days ago");
        }
    ?>
    </table>

    </div>

    <div id='bottom'>
    </div>

</body>
</html>