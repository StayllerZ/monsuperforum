<?php

if ($_POST && isset($_POST['subjectName']) && strlen($_POST['subjectName']) > 0) {

    // Protection XSS : Utilisation de la fonction htmlspecialchars() pour convertir/encoder les caractères spéciaux saisie par l'utilisatuer.

    $subject = htmlspecialchars($_POST['subjectName'], ENT_QUOTES, 'UTF-8');
    insertSubject($subject);
}

function getAllSubjects()
{
    try {
        include 'DBConfig.php';

        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $stmt = $conn->prepare("SELECT sujet.ID_SUJET, sujet.LIBELLE_SUJET FROM sujet"); // requete préparé
    $stmt->execute();
    $index = 1;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div><a href=\"./src/views/message-view.php?subject=" . $row["LIBELLE_SUJET"] . "&id=" . $row["ID_SUJET"] . "\">" . $index . " - " . $row["LIBELLE_SUJET"] . "</a></div>";
        $index++;
    }
}

function insertSubject($subject)
{
    try {
        include 'DBConfig.php';

        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $reqInsertSubject = $conn->prepare("INSERT INTO sujet (LIBELLE_SUJET, DAT_CREA_SUJET, ID_USER)
    VALUES (:LIBELLE_SUJET, :DAT_CREA_SUJET, :ID_USER);");

    $todayDate = date("d-m-Y H:i:s");
    $idUser = 2;

    $reqInsertSubject->bindParam(':LIBELLE_SUJET', $subject);
    $reqInsertSubject->bindParam(':DAT_CREA_SUJET', $todayDate);
    $reqInsertSubject->bindParam(':ID_USER', $idUser);

    $reqInsertSubject->execute();
}


function checkIfConnect()
{
    if (!isset($_COOKIE["PHPSESSID"])) {
        header('Location: ./src/views/login-view.php');
    }
}
