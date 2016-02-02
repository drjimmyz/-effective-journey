<?php
    require_once 'private/check_login.php';
    if ($user_class != 'admin')
    {
        header('Location: index.php');
        exit();
    }
?>