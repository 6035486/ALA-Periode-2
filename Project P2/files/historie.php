<?php
require_once('../helpers/helpers.php');

session_start(); 
$email = $_SESSION['email'];
$user = new User();
$serie = new Serie();
$episodes = $serie->show($email);
$stream = new Stream();
$profileData = $user->getProfile($email);
$totalWatchTime = $stream->totalWatchTime($profileData[0]['KlantNr']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>  
            <a href="./home.php">Home</a>
            <a href="#">Contact</a>
            <a href="./profile.php">Profile</a>
            <a href="historie.php">history</a>
            <a href="./uitlog.php">Logout</a>
        </article>
    </nav> 
    <main>
    <p class="profile__item"><strong>Total Watch Time:</strong> <?php echo $totalWatchTime; ?> minutes</p> 
<div class="container">  
    <h2>Onlangs Bekeken:</h2>
    <?php foreach($episodes as $episode): ?>
        <div class="episode-group">
            <h3><?php echo $episode['SerieTitel']; ?></h3>
            <div class="carousel-view">
                <button class="prev-btn">&#129084;</button>
                <div class="item-list">
                    <?php 
                    $afleveringIds = explode(',', $episode['aflevering_ids']);
                    $afleveringTitels = explode(',', $episode['aflevering_titels']);
                    $startDates = explode(',', $episode['start_dates']);
                    $endDates = explode(',', $episode['end_dates']);

                    for ($i = 0; $i < count($afleveringIds); $i++) { ?>
                        <div class="carousel-item">
                            <img src="../images/dummy.png" alt="Episode Image">
                            <p><?php echo $afleveringTitels[$i]; ?></p>
                           
                            <p>Start:<?php echo $startDates[$i]; ?></p>
                            <p>Eind:<?php echo $endDates[$i]; ?></p>
                        </div>
                    <?php } ?>
                </div>
                <button class="next-btn">&#129086;</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</main>
</body>
</html>
<script>
   const prev = document.getElementById('prev-btn');
const next = document.getElementById('next-btn');
const list = document.getElementById('item-list');
const itemWidth = 150;
const padding = 10;

prev.addEventListener('click', () => {
    list.scrollLeft -= (itemWidth + padding);
});

next.addEventListener('click', () => {
    list.scrollLeft += (itemWidth + padding);
});
</script>