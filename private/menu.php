<ul id='menu'>
    <li class='menu-item'><a href='index.php'>Home</a></li>
    <?php echo "<li class='menu-item'><a href='movie_list.php?user_id=$ini_id'>Movie list</a></li>"; ?>
    <li class='menu-item'><a href='user_list.php'>User list</a></li>
    <li class='menu-item'><a href='images.php'>Images</a></li>
    <li class='menu-item'><a href='books.php'>Books</a></li>
    <?php
    if ($user_class=='admin')
    {
        echo "<div class='dropdown-limiter'>";
        echo "<li class='menu-item'><a id='menu-dropdown-trigger'>Admin</a></li>";
        echo "<div class='dropdown-panel' id='dropdown-menu'>";
        echo "<ul class='dropdown-list'>";
        echo "<li class='menu-item'><a href='images.php'>Pictures</a></li>";
        echo "<li class='menu-item'>Item2</li>";
        echo "<li class='menu-item'>Item3</li>";
        echo "<li class='menu-item'>Item4</li>";
        echo "</ul>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</ul>
</div>