<?php
session_start();
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("admin.php");
    exit();
}
require_once('../helpers/helpers.php');
$series = getActiveSeries($db);
if (isset($_POST['search'])) {
    $series = adminSearch($db, $_POST['search']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>Hobo CMS</h1>
    </header>
    <main>
        <section class="search">
            <form method="post">
                <input type="text" name="search" id="">
                <input type="submit" value="search">
            </form>
        </section>
        <section class="series">
            <?php
                foreach ($series as $serie) {
                    ?>
                        <article>
                            <p><?php echo $serie["SerieTitel"] ?></p>
                            <p>Actief: <?php if ($serie["Actief"] == 1) {
                                echo "Ja";
                            }else {
                                echo "nee";
                            } ?></p>
                        </article>
                    <?php }
                    ?>
        </section>
    </main>
</body>
</html>