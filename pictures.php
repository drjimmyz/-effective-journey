<?php
    require_once 'private/check_login.php';
    if ($user_class != 'admin')
    {
        header('Location: index.php');
        exit();
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
</div>

<div id='bottom'>
</div>
<script src="js/dropdown-menu.js"></script>
</body>
</html>