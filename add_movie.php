<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    $submit_string = 'Add entry';
    $entry_input = '';

    $title = $director = $year = $imdb_rating = $rating = $date = '';
    $fail = '';

    if (isset($_POST['title']))
    {
        $title = fix_string($_POST['title']);

        if (isset($_POST['director'])) $director = fix_string($_POST['director']);
        if (isset($_POST['year'])) $year = fix_string($_POST['year']);
        if (isset($_POST['imdb_rating'])) $imdb_rating = fix_string($_POST['imdb_rating']);
        if (isset($_POST['rating'])) $rating = fix_string($_POST['rating']);
        if (isset($_POST['date'])) $date = fix_string($_POST['date']);

        $fail = validate_title($title);
        $fail .= validate_director($director);
        $fail .= validate_year($year);
        $fail .= validate_rating($rating);
        $fail .= validate_imdb_rating($imdb_rating);
        $fail .= validate_date($date);

        if ($fail == '')
        {
            $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
            $ti_temp = mysql_entities_fix_string($connection, $title);
            $di_temp = mysql_entities_fix_string($connection, $director);
            $ye_temp = mysql_entities_fix_string($connection, $year);
            $im_temp = mysql_entities_fix_string($connection, $imdb_rating);
            $ra_temp = mysql_entities_fix_string($connection, $rating);
            $da_temp = mysql_entities_fix_string($connection, $date);

            if (isset($_POST['entry_id']))
            {
                $ei_temp = mysql_entities_fix_string($connection, $_POST['entry_id']);
                $query = "UPDATE movies SET title='$ti_temp', director='$di_temp', year='$ye_temp',
                          imdb_rating='$im_temp', rating='$ra_temp', date='$da_temp'
                          WHERE user_id='$user_id' AND entry_id='$ei_temp'";
            }

            else $query = "INSERT INTO movies(title, director, year, imdb_rating, rating, date, user_id)
                      VALUES('$ti_temp', '$di_temp', '$ye_temp', '$im_temp', '$ra_temp', '$da_temp',
                             '$user_id')";

            $result = $connection->query($query);
            if (!$result) die($connection->error);
            header('Location: movie_list.php');
        }
    }

    // Reset variables if they have been used earlier.
    $title = $director = $year = $imdb_rating = $rating = $date = '';

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
            $director = html_entity_decode($row['director']);
            $year = html_entity_decode($row['year']);
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

    function validate_director($field)
    {
        if (strlen($field) > 64) return "Stop it.";
        elseif (!preg_match("/^[a-zA-Z 0-9]+$/", $field)) return "Invalid title. Currently only accepts a-z, A-Z and 0-9.";
        else return "";
    }

    function validate_year($field)
    {
        if (strlen($field) > 4) return "Stop it. Too long";
        elseif (!preg_match("/\d\d\d\d/", $field)) return "Invalid year.";
        else return '';
    }

    function validate_imdb_rating($field)
    {
        if (strlen($field) > 3) return "Stop it.";
        elseif (!preg_match("/^[\d.]{1,3}$/", $field)) return "Invalid imdb rating format.";
        else return '';
    }

    function validate_rating($field)
    {
        if (strlen($field) > 3) return "Stop it.";
        elseif (!preg_match("/^[\d.]{1,3}$/", $field)) return "Invalid rating format.";
        else return '';
    }

    function validate_date($field)
    {
        if (strlen($field) > 10) return "Stop it. Too long";
        elseif (!preg_match("/\d\d\d\d-\d\d-\d\d/", $field) && $field != '') return "Invalid date.";
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
    <script src="js/jquery.xdomainajax.js"></script>
</head>

<body>

    <div id='user_display'>
    You are logged in as: <?php echo $username; ?><br><a href='logout.php'>Log out</a>
    </div>

    <div id='top'>
    <img src='pictures/logo.png' id='logo'>

    <?php include_once 'private/menu.php'; ?>
    
    </div>

    <div id='main'>
    <div class='px20div'> </div>

    <div class='single-input'>
    <input id='imdb_link' type='text' name='imdb_link' placeholder='IMDB link'>
    <img src='icons/ajax-loader.gif' id='loading_image'>
    </div>
    <button type='button' id='fetch_imdb' onclick='get_link()'>Auto-fill information</button>

    <div id='form_holder'>
    <form method='post' action='add_movie.php'>
    <?php
    echo "<input type='text' id='title' name='title' placeholder='Movie title' maxlength='64' required value='$title'>";
    echo "<input type='text' id='director' name='director' placeholder='Director' maxlength='64' value='$director'>";
    echo "<input type='text' id='year' name='year'  placeholder='Year' maxlength='4' value='$year'>";
    echo "<input type='text' id='imdb_rating' name='imdb_rating'  placeholder='IMDB rating' maxlength='3' value='$imdb_rating'>";

    echo "<input type='text' name='rating'  placeholder='My rating' maxlength='3' value='$rating'>";
    echo "<input type='text' name='date' placeholder='Date seen (in format yyyy-mm-dd)' maxlength='10' value='$date'>";
    echo "$entry_input";
    echo "<input class='stnd-button' type='submit' name='submit' value='$submit_string'>";
    ?>
    </form>

    </div>
    <?php echo $fail ?>
    </div>

    <div id='bottom'>
    </div>

<script>

function get_link() {
    check_imdb($('#imdb_link').val());
    $('#loading_image').css('display', 'inline');
    $('#imdb_link').val('');
}

function check_imdb(URL)
{
    $.ajax({
        url: URL,
        type: 'GET',
        success: function(res) {
            add_information(res.responseText);
        },

        complete: function() {
            $('#loading_image').css('display', 'none');
        }

    });
}

function add_information(text) {
    rating = $(text).find("span[itemprop='ratingValue']").text();
    title = $(text).find("h1[itemprop='name']").html();
    year = $(text).find("span#titleYear > a").text();
    director = $(text).find("span[itemprop='director'] > a > span").text();
    title = title.split("&nbsp");
    title = $('<textarea />').html(title[0]).text();
    $("#imdb_rating").val(rating);
    $("#title").val(title);
    $("#director").val(director);
    $("#year").val(year);
}
</script>


<script src="js/dropdown-menu.js"></script>
</body>
</html>

<?php $connection->close(); ?>