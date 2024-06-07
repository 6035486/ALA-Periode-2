<?php
session_start();
if (!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true) {
    header("Location: admin.php");
    exit();
}

require_once('../helpers/helpers.php');

$serie = new Serie();

if (isset($_POST["page"])) {
    $page = (int)$_POST["page"];
} else {
    $page = 0;
}
$series = $serie->adminSearch(false, $page);
if (isset($_POST['search'])) {
    $series = $serie->adminSearch($_POST['search'], $page);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin">
    <header>
        <h1>Hobo CMS</h1>
    </header>
    <main>
        <section class="search">
            <form method="post">
                <input type="text" name="search" id="">
                <input type="submit" value="search">
                <input type="hidden" name="page" <?php echo 'value="'.$page.'"'; ?> id="">
            </form>
        </section>  
        <section class="series">
            <?php
                foreach ($series as $serie) {
                    ?>
                        <article <?php if ($serie["Actief"] == 1) { echo "class='actief'"; }?>>
                            <p><?php echo $serie["SerieTitel"] ?></>
                            <p >Actief: <?php if ($serie["Actief"] == 1) {
                                echo "Ja";
                            }else {
                                echo "nee";
                            } ?></p>
                            <?php echo "<p>".$serie['GenreNaam']."</p>" ?>
                            <form action="../post/activate.php" method="post">
                                <?php echo '<input type="hidden" name="serieId" value="'.$serie["SerieID"].'">'; ?>
                                <input type="submit" value="(de)activeer">
                            </form>
                        </article>
                    <?php }
                    ?>
        </section>
    </main>
</body>
</html>