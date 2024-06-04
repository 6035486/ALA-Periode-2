<?php
require_once('../helpers/helpers.php');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
if(isset($_POST['submit'])){
    changeProfile($db);
}

$email = $_SESSION['email'];
$profileData = getProfile($db, $email);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $email = $_SESSION['email']; 
    $rowsDeleted = deleteAccount($db, $email);

    if ($rowsDeleted > 0) {
        
        session_destroy(); 
        header("Location: delete.php"); 
        exit();
    } else {
        $error = "Failed to delete the account. Please try again.";
    }
}
$email = $_SESSION['email'];
$episodes = show($db, $email);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
        <img src="../images/HOBO_logo.png" alt="Logo">
        <article>
            <a href="./home.php">Home</a>
            <a href="#">Contact</a>
            <a href="#">Profile</a>
            <a href="./uitlog.php">Logout</a>
        </article>
    </nav>
    <main class="profile">
    <h2 class="profile__title">Profile</h2>
    <?php
    if ($profileData && is_array($profileData)) {
        foreach($profileData as $data) {
    ?>
    <div class="profile__details">
        <p class="profile__item"><strong>First Name:</strong> <?php echo $data['Voornaam']; ?></p>
        <p class="profile__item"><strong>Last Name:</strong> <?php echo $data['Achternaam']; ?></p>
        <p class="profile__item"><strong>Email:</strong> <?php echo $data['Email']; ?></p>
        <p class="profile__item"><strong>Genre:</strong> <?php echo $data['Genre']; ?></p>
    </div>
    
    <button class="profile__edit-button" type="submit" name="delete">Delete Account</button>
    <button class="profile__edit-button" onclick="document.getElementById('editProfileForm').style.display='block'">Edit Profile</button>
    <div id="editProfileForm" class="profile__edit-form" style="display: none;">
        <form method="POST">
            <label for="Voornaam">First Name:</label>
            <input type="text" id="Voornaam" name="Voornaam" value="<?php echo $data['Voornaam']; ?>">
                    
            <label for="Achternaam">Last Name:</label>
            <input type="text" id="Achternaam" name="Achternaam" value="<?php echo $data['Achternaam'] ?>">
                    
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" value="<?php echo $data['Email']; ?>">
                    
            <label for="Genre">Genre:</label>
            <select name="Genre" id="Genre" required>
                <?php
                $sql = "SELECT GenreNaam FROM genre";
                $stm = $db->prepare($sql);
                $stm->execute();    
                $result = $stm->fetchAll();
                foreach ($result as $row) {
                    $selected = ($row["GenreNaam"] == $data['Genre']) ? 'selected' : '';
                    echo "<option class='' value='".$row["GenreNaam"]."' $selected>".$row["GenreNaam"]."</option>";
                }
                ?>
            </select>
                    
            <button type="submit" name="submit" class="profile__save-button">Save Changes</button>
            <button type="button" class="profile__cancel-button" onclick="document.getElementById('editProfileForm').style.display='none'">Cancel</button>
        </form>
    </div>
    <?php 
        } // End of foreach loop
    } else {
        echo "No profile data found.";
    }
    ?>
    <section class="episodes" >
        <article class="episode">
            <h2>Onlangs Bekeken:</h2>
            <?php foreach($episodes as $episode){ ?>
                <img src="../images/dummy.png" alt="Episode Image">
                <p><?php echo $episode['SerieTitel']; ?></p>
                <p><?php echo $episode['AflTitel']; ?></p>
                <p><?php echo $episode['Rang']; ?></p>
                <p><?php echo $episode['d_start']; ?></p>
                <p><?php echo $episode['d_eind']; ?></p>
                <?php } ?>
        </article>
    </section>
</main>

    <footer class="footer">
    <div class="footer__container">
        <div class="footer__info">
            <h3 class="footer__title">Bedrijfsnaam</h3>
            <p>1234 Adresstraat,<br>Stad, Land</p>
            <p>Telefoon: (123) 456-7890</p>
            <p>Email: info@bedrijf.nl</p>
        </div>

        <div class="footer__links">
            <h3 class="footer__title">Snelle Links</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Over Ons</a></li>
                <li><a href="#">Diensten</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer__social">
            <h3 class="footer__title">Volg ons</h3>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">LinkedIn</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>
</html>