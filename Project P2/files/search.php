<?php
require_once('../helpers/helpers.php');
if (isset($_POST["query"])) {
    $results = search($db, $_POST["query"]);
}
$imageSrc = "../images/"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="search">
    <header>
        <form method="post">
            <input type="text" name="query" id="">
            <input type="submit" value="submit">
        </form>
    </header>
    <main>
        <?php if (isset($results)) {
             foreach($results as $result) { 
                if ($result["Actief"] == "1") {
                    ?> <div>
                        <img src="<?php switch (strlen($result["SerieID"])) {
                            case 1:
                                echo $imageSrc . "0000" . $result["SerieID"] . ".jpg";
                                break;
                            
                            case 2:
                                echo $imageSrc . "000" . $result["SerieID"] . ".jpg";
                                break;
                            
                            case 3:
                                echo $imageSrc . "00" . $result["SerieID"] . ".jpg";
                                break;
                            case 4:
                                echo $imageSrc . "0" . $result["SerieID"] . ".jpg";
                                break;
                            
                            default:
                                # code...
                                break;
                        } ?>" alt="" srcset="">
                    </div> <?php
                } else {
                }
              }}?>
    </main>
</body>
</html>