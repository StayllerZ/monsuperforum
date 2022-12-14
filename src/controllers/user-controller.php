<?php

function checkIfRegister()
{

    if (isset($_SESSION['User'])) {
        header('Location: http://localhost/secuForum/index.php');
    } else {
        if (isset($_POST['name'])) {
            if (isset($_POST['password'])) {
                insertNewUser();
                // Création d'un cookie ayant pour valeur le mdp hashé
            }
        }
    }
}


function insertNewUser()
{

    include 'DBConfig.php';
    try {
        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $todaydate = date("Y/m/d");
    $login = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);
    $hashpass = password_hash($password, PASSWORD_DEFAULT); // hash du mdp

    // VERIFICATION SI LE USER EXISTE DEJA
    $veriff = $conn->prepare('SELECT ID_USER, LOGIN_USER, PASSWORD_USER FROM utilisateur WHERE LOGIN_USER = ?'); // requete préparé
    $veriff->execute([$login]);

    $founditem = 0;
    while ($row = $veriff->fetch(PDO::FETCH_ASSOC)) {
        $founditem = $founditem + 1;
        $idUser = $row["ID_USER"];
    }
    // INSERTION DU USER

    if ($founditem == 0) {
        $stmt = $conn->prepare("INSERT INTO utilisateur (LOGIN_USER, PASSWORD_USER, PRIVILEGE_USER, DATE_INSCR_USER, isBlocked, IMG_USER) VALUES (:login, :hashpass, 0, :todaydate, 0, 'default.png')"); // requete préparé
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':hashpass', $hashpass);
        $stmt->bindParam(':todaydate', $todaydate);
        $stmt->execute();

        $veriff = $conn->prepare('SELECT ID_USER, LOGIN_USER, PASSWORD_USER FROM utilisateur WHERE LOGIN_USER = ?'); // requete préparé
        $veriff->execute([$login]);

        while ($row = $veriff->fetch(PDO::FETCH_ASSOC)) {
            $idUser = $row["ID_USER"];
        }


        session_start();
        $_SESSION["User"] = $login;
        $_SESSION["Id"] = $idUser;
        header('Location: ../../index.php');
    }
    if ($founditem != 0) {
        echo '<p>User already exist try login</p>';
    }
}






function checkConnection()
{
    if (!empty($_POST)) {
        if (!isset($_SESSION['User'])) {
            include 'DBConfig.php';
            try {
                $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
            } catch (PDOException $e) {
                die($e);
            }

            $login = htmlspecialchars($_POST['name']);
            $password = htmlspecialchars($_POST['password']);
            $hashpass = password_hash($password, PASSWORD_DEFAULT); // hash du mdp

            $stmt = $conn->prepare('SELECT ID_USER, LOGIN_USER, PASSWORD_USER, isBlocked FROM utilisateur WHERE LOGIN_USER = ?'); // requete préparé
            $stmt->execute([$login]);


            $founditem = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bddhash = $row["PASSWORD_USER"];
                $founditem = $founditem + 1;
                $blockState = $row["isBlocked"]; // bloqué ou non
                $idUser = $row["ID_USER"];
            }
            if ($founditem == 0) {
                echo '<p>Incorrect Password</p>';
            }
            if ($founditem == 1) {
                if (password_verify($password, $bddhash) && $blockState == 0) { // vérification si user et password et bon et si le user n'est pas bloquée
                    session_start();
                    $_SESSION["User"] = $login;
                    $_SESSION["Id"] = $idUser;
                    echo '<p>Succesfully login</p>';
                    header('Location: ../../index.php');
                }
            }
        }
    }
}

function alreadyLoginCheck()
{
    if (!isset($_COOKIE["PHPSESSID"])) {
        echo '<p>pas connecter</p>';
    } else {


        header('Location: ../../index.php');
    }
}
