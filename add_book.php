<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';

    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    $submit_string = 'Add entry';
    $entry_input = '';

    $title = $author = $total_pages = '';
    $fail = '';

    if (isset($_POST['title']))
    {
        $title = fix_string($_POST['title']);

        if (isset($_POST['author'])) $author = fix_string($_POST['author']);
        if (isset($_POST['total_pages'])) $total_pages = fix_string($_POST['total_pages']);

        $fail = validate_title($title);
        $fail .= validate_author($author);
        $fail .= validate_total_pages($total_pages);

        if ($fail == '')
        {
            $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
            $ti_temp = mysql_entities_fix_string($connection, $title);
            $au_temp = mysql_entities_fix_string($connection, $author);
            $to_temp = mysql_entities_fix_string($connection, $total_pages);

            if (isset($_POST['entry_id']))
            {
                $ei_temp = mysql_entities_fix_string($connection, $_POST['entry_id']);
                $query = "UPDATE movies SET title='$ti_temp', author='$di_temp', total_pages='$ye_temp',
                          imdb_rating='$im_temp', rating='$ra_temp', date='$da_temp'
                          WHERE user_id='$user_id' AND entry_id='$ei_temp'";
            }

            else $query = "INSERT INTO user_books(title, author, total_pages, user_id)
                           VALUES('$ti_temp', '$au_temp', '$to_temp', '$user_id')";

            $result = $connection->query($query);
            if (!$result) die($connection->error);
            header('Location: books.php');
        }
    }

    if (isset($_GET['entry_id']))
    {
        $entry_id = fix_string($_GET['entry_id']);
        $entry_id = mysql_entities_fix_string($connection, $entry_id);

        $query = "SELECT * FROM movies WHERE entry_id='$entry_id' AND user_id='$user_id'";
        $result = $connection->query($query);
        if (!$result) die($connection->error);

        elseif ($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $title = html_entity_decode($row['title']);
            $author = html_entity_decode($row['author']);
            $total_pages = html_entity_decode($row['total_pages']);
            $imdb_rating = html_entity_decode($row['imdb_rating']);
            $rating = html_entity_decode($row['rating']);
            $date = html_entity_decode($row['date']);

            $submit_string = 'Edit entry';
            $entry_input = "<input type='hidden' name='entry_id' value='" . $row['entry_id'] . "'>";
        }
        else header('Location: add_movie.php');

        
        $connection->close();

    }


    function validate_title($field)
    {
        if (strlen($field) > 64) return "Stop it.";
        elseif (!preg_match("/^[a-zA-Z0-9 ']+$/", $field)) return "Invalid title. Currently only accepts a-z, A-Z and 0-9.";
        else return "";
    }

    function validate_author($field)
    {
        if (strlen($field) > 64) return "Stop it.";
        elseif (!preg_match("/^[a-zA-Z 0-9]+$/", $field)) return "Invalid author. Currently only accepts a-z, A-Z and 0-9.";
        else return "";
    }

    function validate_total_pages($field)
    {
        if (!preg_match("/^[1-9]\d{0,5}$/", $field)) return "Invalid pages.";
        else return '';
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
    <link rel='stylesheet' type='text/css' href='css/main.css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>

<body>
<?php include_once 'private/top_and_menu.php'; ?>

<div id='main'>

<div id='form_holder'>
<form method='post' action='add_book.php'>
<?php
echo "<input type='text' id='title' name='title' placeholder='Book title' maxlength='64' required value='$title'>";
echo "<input type='text' id='author' name='author' placeholder='Author' maxlength='64' value='$author'>";
echo "<input type='text' id='total_pages' name='total_pages'  placeholder='Number of pages' maxlength='10' value='$total_pages'>";
echo "$entry_input";
echo "<input class='stnd-button' type='submit' name='submit' value='$submit_string'>";
?>
</form>

</div>
<?php echo $fail ?>
</div>

<div id='bottom'>
</div>

<script src="js/dropdown-menu.js"></script>
</body>
</html>

<?php $connection->close(); ?>