<?php
require_once('../helpers/helpers.php');

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

$user = new User();
$serie = new Serie();
$stream = new Stream();

if (isset($_POST['submit'])) {
    $user->changeProfile();
}

$email = $_SESSION['email'];
$profileData = $user->getProfile($email);
$totalWatchTime = $stream->totalWatchTime($profileData[0]['KlantNr']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $rowsDeleted = $user->deleteAccount($email);

    if ($rowsDeleted > 0) {
        session_destroy(); 
        header("Location: delete.php"); 
        exit();
    } else {
        $error = "Failed to delete the account. Please try again.";
    }
}

$episodes = $serie->show($email);
$genre = new Genre();
$genres = $genre->getAllGenres();
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
            <a href="./profile.php">Profile</a>
            <a href="historie.php">History</a>
            <a href="./uitlog.php">Logout</a>
            <a href="./search.php">Search</a>
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
            <select name="Genre">
    <?php foreach ($genres as $genre) {
        $selected = ($genre == $data['Genre']) ? 'selected' : '';
        echo "<option class='' value='".$genre."' $selected>".$genre."</option>";
    } ?>
</select>
                    
            <button type="submit" name="submit" class="profile__save-button">Save Changes</button>
            <button type="button" class="profile__cancel-button" onclick="document.getElementById('editProfileForm').style.display='none'">Cancel</button>
        </form>
    </div>
    <?php 
        } 
    } else {
        echo "No profile data found.";
    }
    ?>
   <div class="privacy">
        <h1>Privacy Policy</h1>

        <h2>Introduction</h2>
        <p>Welcome to HOBO. We value your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website (regardless of where you visit it from) and tell you about your privacy rights and how the law protects you.</p>

        <h2>Information We Collect</h2>
        <p>We may collect and process the following data about you:</p>
        <ul>
            <li>Information you provide by filling in forms on our site.</li>
            <li>Details of your visits to our site and the resources that you access.</li>
            <li>Information about your computer, including your IP address, operating system, and browser type.</li>
        </ul>

        <h2>How We Use Your Information</h2>
        <p>We use information held about you in the following ways:</p>
        <ul>
            <li>To ensure that content from our site is presented in the most effective manner for you and for your computer.</li>
            <li>To provide you with information, products, or services that you request from us or which we feel may interest you.</li>
            <li>To notify you about changes to our service.</li>
        </ul>

        <h2>Sharing Your Information</h2>
        <p>We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential.</p>

        <h2>Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Request access to your personal data.</li>
            <li>Request correction of the personal data that we hold about you.</li>
            <li>Request erasure of your personal data.</li>
            <li>Object to processing of your personal data.</li>
            <li>Request the restriction of processing of your personal data.</li>
            <li>Request the transfer of your personal data to another party.</li>
        </ul>

        <h2>Data Security</h2>
        <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered, or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors, and other third parties who have a business need to know.</p>

        <h2>Changes to Our Privacy Policy</h2>
        <p>We keep our privacy policy under regular review, and we will place any updates on this web page. This privacy policy was last updated on [Date].</p>

        <h2>Contact Us</h2>
        <p>If you have any questions about this privacy policy or our privacy practices, please contact us at:</p>
        <p>Email: info@bedrijf.nl</p>
        <p>Address: 1234 Adresstraat</p>
        <form >
        <div class="checkbox-container">
                <input type="checkbox" id="privacy" name="privacy" required>
                <label for="privacy" class="privacy-policy">
                    Ik ga akkoord met het <a href="/privacy-policy">privacybeleid</a>.
                </label>
            </div>
        </form>
    </div>

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
