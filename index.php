<?php
    require_once 'private/check_login.php';

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
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
        <?php echo"<li><a href='movie_list.php?user_id=$user_id'>Movie list</a></li>"; ?>
        
        <li><a href='user_list.php'>User list</a></li>
    </ul>
    </div>



    <div id='main'>

    <div class='content-box'>
    <h2>This is a heading!</h2>
    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pellentesque arcu a pharetra luctus. Nulla vel augue ac purus vulputate imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent ullamcorper pretium lectus, eget semper dui consequat at. Suspendisse auctor elit purus, eu accumsan libero accumsan porta. Sed cursus eget lectus vel malesuada. Maecenas pellentesque velit nec nulla faucibus tristique viverra varius velit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et porta ante. Maecenas porttitor sem pulvinar, vulputate eros et, porttitor orci. Nulla eget blandit odio. </p>

    <p> Proin sodales nibh a est viverra tempor sit amet at orci. Curabitur pulvinar posuere felis, eu blandit turpis. Sed rhoncus turpis tincidunt sem consectetur, non condimentum quam hendrerit. Aliquam erat lorem, iaculis cursus dapibus sit amet, tempor ut quam. Morbi in mi leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras et ultricies ex. Phasellus mi urna, posuere ac egestas et, lobortis vitae ligula. Praesent condimentum iaculis lacus, ut ornare augue bibendum viverra. Cras vitae porta nisl, suscipit semper dolor. Nullam non pellentesque lorem. Mauris malesuada est vitae lobortis laoreet. </p>
    </div>

    <div class='content-box'>
    <h2>This is a heading!</h2>
    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pellentesque arcu a pharetra luctus. Nulla vel augue ac purus vulputate imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent ullamcorper pretium lectus, eget semper dui consequat at. Suspendisse auctor elit purus, eu accumsan libero accumsan porta. Sed cursus eget lectus vel malesuada. Maecenas pellentesque velit nec nulla faucibus tristique viverra varius velit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et porta ante. Maecenas porttitor sem pulvinar, vulputate eros et, porttitor orci. Nulla eget blandit odio. </p>

    <p> Proin sodales nibh a est viverra tempor sit amet at orci. Curabitur pulvinar posuere felis, eu blandit turpis. Sed rhoncus turpis tincidunt sem consectetur, non condimentum quam hendrerit. Aliquam erat lorem, iaculis cursus dapibus sit amet, tempor ut quam. Morbi in mi leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras et ultricies ex. Phasellus mi urna, posuere ac egestas et, lobortis vitae ligula. Praesent condimentum iaculis lacus, ut ornare augue bibendum viverra. Cras vitae porta nisl, suscipit semper dolor. Nullam non pellentesque lorem. Mauris malesuada est vitae lobortis laoreet. </p>
    </div>

    <div class='content-box'>
    <h2>This is a heading!</h2>
    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pellentesque arcu a pharetra luctus. Nulla vel augue ac purus vulputate imperdiet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent ullamcorper pretium lectus, eget semper dui consequat at. Suspendisse auctor elit purus, eu accumsan libero accumsan porta. Sed cursus eget lectus vel malesuada. Maecenas pellentesque velit nec nulla faucibus tristique viverra varius velit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi et porta ante. Maecenas porttitor sem pulvinar, vulputate eros et, porttitor orci. Nulla eget blandit odio. </p>

    <p> Proin sodales nibh a est viverra tempor sit amet at orci. Curabitur pulvinar posuere felis, eu blandit turpis. Sed rhoncus turpis tincidunt sem consectetur, non condimentum quam hendrerit. Aliquam erat lorem, iaculis cursus dapibus sit amet, tempor ut quam. Morbi in mi leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras et ultricies ex. Phasellus mi urna, posuere ac egestas et, lobortis vitae ligula. Praesent condimentum iaculis lacus, ut ornare augue bibendum viverra. Cras vitae porta nisl, suscipit semper dolor. Nullam non pellentesque lorem. Mauris malesuada est vitae lobortis laoreet. </p>
    </div>

    </div>
    <div id='bottom'>
    </div>

</body>
</html>