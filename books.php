<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    $book_ids = array();
    $book_titles = array();
    $now = new DateTime(date("Y-m-d H:i:s"));
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
<div class='column-books'>
<h2>Books</h2><br><br>
<table id='ratings'>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Read pages</th>
    <th>Total pages</th>
</tr>

    <?php
        $query = "SELECT * FROM user_books WHERE user_id='$user_id'";
        $result = $connection->query($query);
        if (!$result) die($connection->error);

        $rows = $result->num_rows;
        
        for ($j = 0; $j < $rows; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if ($j % 2 == 0) echo "<tr class='even'>";
            else echo '<tr>';

            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['author'] . '</td>';
            echo '<td>' . $row['read_pages'] . '</td>';
            echo '<td>' . $row['total_pages'] . '</td>';
            array_push($book_ids, $row['book_id']);
            array_push($book_titles, $row['title']);
        }

        $result->close();

    ?>
</table>
</div>

<div class='column-updates'>
<h2>Updates</h2><br><br>
<?php
    for ($i = 0; $i < count($book_ids); $i++)
    {

        $query = "SELECT * FROM book_updates WHERE book_id='$book_ids[0]'";
        $result = $connection->query($query);
        if (!$result) die($connection->error);

        $rows = $result->num_rows;
        for ($j = 0; $j < $rows; ++$j)
        {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $pages = $row['read_pages'];
            $time_ago = time_dif($row['added'], $now);

            echo "<div class='update-box'>";
            echo "$time_ago:<br>$username read $pages pages of $book_titles[$i].";
            echo "</div>";
        }
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

</div>


</div>
<div id='bottom'>
</div>
<script src="js/dropdown-menu.js"></script>
</body>
</html>

<?php $connection->close(); ?>