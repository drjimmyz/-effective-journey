<?php
    require_once 'private/sqldetails.php';
    session_start();

    if (!(isset($_SESSION['username']) && $_SESSION['check'] ==
        hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])))
    {
        header('Location: login.php');
        exit();
    }
    
    else
    {
        $user_id = $_SESSION['user_id'];
        $ini_id = $user_id;
        $user_class = $_SESSION['user_class'];
        $ts_connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
        $ts_query = "UPDATE users SET last_seen=CURRENT_TIMESTAMP WHERE user_id='$user_id'";
        $ts_result = $ts_connection->query($ts_query);
        if (!$ts_result) die($ts_connection->error);
        $ts_connection->close();
    }
?>