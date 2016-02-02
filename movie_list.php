<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $ini_id = $user_id;

    // If it's not the logged in users id, this will be set to true.
    $other_profile = false;

    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    $sort = '';

    if(isset($_POST['entry_id']))
    {
        $ei_temp = mysql_entities_fix_string($connection, $_POST['entry_id']);

        $query = "DELETE FROM movies WHERE entry_id='$ei_temp' AND user_id='$user_id'";
        $result = $connection->query($query);
        if (!$result) die($connection->error);
    }

    if(isset($_GET['user_id']))
    {
        $user_id = mysql_entities_fix_string($connection, $_GET['user_id']);
        $other_profile = true;
    }

    if(isset($_GET['sort']))
    {
        $sort_temp = htmlentities($_GET['sort']);
        switch ($sort_temp)
        {
            case 'date':
                $sort = 'ORDER BY date';
                break;

            case 'date_desc':
                $sort = 'ORDER BY date DESC';
                break;

            case 'dir':
                $sort = 'ORDER BY director';
                break;

            case 'dir_desc':
                $sort = 'ORDER BY director DESC';
                break;

            case 'title':
                $sort = 'ORDER BY title';
                break;

            case 'title_desc':
                $sort = 'ORDER BY title DESC';
                break;

            case 'imdb':
                $sort = 'ORDER BY imdb_rating';
                break;

            case 'imdb_desc':
                $sort = 'ORDER BY imdb_rating DESC';
                break;

            case 'rating':
                $sort = 'ORDER BY rating';
                break;

            case 'rating_desc':
                $sort = 'ORDER BY rating DESC';
                break;

            case 'year':
                $sort = 'ORDER BY year';
                break;

            case 'year_desc':
                $sort = 'ORDER BY year DESC';
                break;

        }
    }
    $query = "SELECT * FROM movies WHERE user_id='$user_id'" . $sort;
?>

<html>
<head>
    <title>Site</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
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
        <li class='menu-item'><a href='index.php'>Home</a></li>
        <?php echo "<li class='menu-item'><a href='movie_list.php?user_id=$ini_id'>Movie list</a></li>"; ?>
        <li class='menu-item'><a href='user_list.php'>User list</a></li>
        <div class='dropdown-limiter'>
            <li class='menu-item'><a id='menu-dropdown-trigger' href='#'>Admin</a></li>
            <div class='dropdown-panel' id='dropdown-menu'>
            <ul class='dropdown-list'>
                <li class='menu-item'>Item1</li>
                <li class='menu-item'>Item2</li>
                <li class='menu-item'>Item3</li>
                <li class='menu-item'>Item4</li>
            </ul>
            </div>
        </div>
    </ul>
    </div>



    <div id='main'>
    <table id='video_ratings'>
        <tr>
        <?php
            $user_string = "&user_id=$user_id";
            echo "<th>Movie title";
            echo "<a href='movie_list.php?sort=title$user_string'>▴</a>";
            echo "<a href='movie_list.php?sort=title_desc$user_string'>▾</a>";
            echo "</th>";
            echo "<th>Director";
            echo "<a href='movie_list.php?sort=dir$user_string'>▴</a>";
            echo "<a href='movie_list.php?sort=dir_desc$user_string'>▾</a>";
            echo "</th>";
            echo "<th>Year";
            echo "<a href='movie_list.php?sort=year$user_string'>▴</a>";
            echo "<a href='movie_list.php?sort=year_desc$user_string'>▾</a>";
            echo "</th>";
            echo "<th>IMDB rating";
            echo "<a href='movie_list.php?sort=imdb$user_string'>▴</a>";
            echo "<a href='movie_list.php?sort=imdb_desc$user_string'>▾</a>";
            echo "</th>";
            echo "<th>Date seen";
            echo "<a href='movie_list.php?sort=date$user_string'>▴</a>";
            echo "<a href='movie_list.php?sort=date_desc$user_string'>▾</a>";
            echo "</th>";
            echo "<th>My rating";
            echo "<a href='movie_list.php?sort=rating$user_string'>▴</a>";
            echo "<a href='movie_list.php?sort=rating_desc$user_string'>▾</a>";
            echo "</th>";
        ?>
        </tr>

        <?php
            $result = $connection->query($query);
            if (!$result) die($connection->error);

            $rows = $result->num_rows;
            
            for ($j = 0; $j < $rows; ++$j)
            {
                $result->data_seek($j);
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if ($j % 2 == 0) echo "<tr class='even'>";
                else echo '<tr>';

                echo '<td>' . html_entity_decode($row['title']) . '</td>';
                echo '<td>' . html_entity_decode($row['director']) . '</td>';
                echo '<td>' . html_entity_decode($row['year']) . '</td>';
                echo '<td>' . html_entity_decode($row['imdb_rating']) . '</td>';
                echo '<td>' . html_entity_decode($row['date']) . '</td>';
                echo '<td>' . html_entity_decode($row['rating']) . '</td>';

                if (!$other_profile)
                {
                    echo "<td class='del-edit'><form method='post' action='movie_list.php'><input type='hidden' name='entry_id' value='" . $row['entry_id'] . "'>";
                    echo "<input type='submit' value='Delete' class='stnd-button'></form>";
                    echo "<a class='stnd-button' href='add_movie.php?entry_id=" . $row['entry_id'] . "'>Edit</a></td></tr>";
                }
            }

            $result->close();
            $connection->close();

        ?>
    </table>

    <div class='px20div'> </div>
    <div class='inline-centerer'>

    <?php
    if (!$other_profile)
    {

        echo "<a class='stnd-button' href='add_movie.php'>Add a movie</a>";
        echo "<a class='stnd-button' href='movie_list.php?user_id=$ini_id'>View list</a>";

        echo "</div>";
    }
    elseif ($ini_id == $user_id) echo "<a class='stnd-button' href='movie_list.php'>Edit list</a>";
    ?>

    </div>
    </div>
    <div id='bottom'>
    </div>

    <script src="js/dropdown-menu.js"></script>
</body>
</html>