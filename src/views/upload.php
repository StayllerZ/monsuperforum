<?php
var_dump($_POST);
if ($_POST && isset($_POST['login'])) {
    session_start();
    echo $_SESSION["User"];
    try {
        include '../controllers/DBConfig.php';

        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }

    $newLogin = $_POST["login"];
    $oldLogin = $_SESSION["User"];
    if($newLogin != ""){

        $existingUser = array();
        $stmt = $conn->prepare('SELECT LOGIN_USER from utilisateur;'); // RECUPERATION DES SUJETS
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($existingUser, $row["LOGIN_USER"]);
        }

        if(!in_array($newLogin,$existingUser)){

            $reqUpdate = $conn->prepare("UPDATE utilisateur
                                 SET LOGIN_USER = :newBlockState
                                 WHERE LOGIN_USER = :loginToModify;");

        $reqUpdate->bindParam(':newBlockState', $newLogin);
        $reqUpdate->bindParam(':loginToModify', $oldLogin);

        $reqUpdate->execute();
        $_SESSION["User"] = $newLogin;
        }

    }
    
}


// Get reference to uploaded image
$image_file = $_FILES["image"];
var_dump($_POST["login"]);
if (!empty($_FILES["image"])) {
    $extension  = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

    // Move the temp image file to the images/ directory

    if ($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "PNG" || $extension == "JPG" || $extension == "JPEG") {
        session_start();

        move_uploaded_file(
            // Temp image location
            $image_file["tmp_name"],
            // New image location, __DIR__ is the location of the current PHP file
            __DIR__ . "../../../images/" . $_SESSION["Id"] . "." . $extension
        );

        $imagePath = $_SESSION["Id"] . "." . $extension;
        $oldLogin = $_SESSION["User"];

        $reqUpdate = $conn->prepare("UPDATE utilisateur
                                    SET IMG_USER = :imagePath
                                    WHERE LOGIN_USER = :loginToModify;");

        $reqUpdate->bindParam(':imagePath', $imagePath);
        $reqUpdate->bindParam(':loginToModify', $oldLogin);

        $reqUpdate->execute();




        header("location: profil-view.php");
    }
    header("location: profil-view.php");
} else {
    header("location: profil-view.php");
}
