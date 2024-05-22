<?php
require_once('../helpers/helpers.php');
if (isset($_POST["query"])) {
    $results = search($db ,$_POST["query"]);
    foreach ($results as $row) {
        echo "<p>".$row["SerieTitel"]."<br>".$row["IMDBLink"]. "</p><br><br><br>";
    }
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="text" name="query" id="">
        <input type="submit" value="submit">
    </form>
</body>
</html>