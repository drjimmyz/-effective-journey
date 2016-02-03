<html>
<head>
<style>
* {
    padding: 0px;
    margin: 0px;
}

img {
    max-width: 500px;
}
</style>
</head>
<body>

<?php
    if (isset($_GET['src']))
    {
        echo "<img src='" . $_GET['src'] . "'>";
    }
?>

</body>
</html>