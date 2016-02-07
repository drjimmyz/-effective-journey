<?php
    require_once 'private/check_login.php';
    require_once 'private/sqldetails.php';
    require_once 'private/mysql_fix_string.php';
    $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
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



</div>
<div id='bottom'>
</div>
<script src="js/dropdown-menu.js"></script>
</body>
</html>