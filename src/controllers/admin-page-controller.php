<?php

function displayUser()
{
    include 'DBConfig.php';
    try {
        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $stmt = $conn->prepare('SELECT LOGIN_USER, isBlocked FROM utilisateur'); // requete préparé
    $stmt->execute();
    echo "0 = pas bloqué, 1 = bloqué";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div>" . $row['LOGIN_USER'] . " est a l'état : " . $row['isBlocked'] . "</div>";
    }
}

function changeBlockState()
{
    var_dump($_POST);
    if (isset($_POST['name']) && isset($_POST['block'])) {

        try {
            include 'DBConfig.php';

            $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
        } catch (PDOException $e) {
            die($e);
        }


        $newBlockState = $_POST['block'];
        $loginToModify = $_POST['name'];

        $reqUpdate = $conn->prepare("UPDATE utilisateur
                                     SET isBlocked = :newBlockState
                                     WHERE LOGIN_USER = :loginToModify;");

        $reqUpdate->bindParam(':newBlockState', $newBlockState);
        $reqUpdate->bindParam(':loginToModify', $loginToModify);

        $reqUpdate->execute();

        echo '<p> User state has been change please refresh the page </p>';
    }

    if (isset($_POST["nameSubject"])) {
        try {
            include 'DBConfig.php';

            $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
        } catch (PDOException $e) {
            die($e);
        }


        $subjectToRemove = $_POST["nameSubject"];


        // SUPPRESSION DES MESSAGES

        $reqUpdate = $conn->prepare("DELETE FROM message_sujet
                                     WHERE ID_SUJET = :subjectToRemove;");

        $reqUpdate->bindParam(':subjectToRemove', $subjectToRemove);

        $reqUpdate->execute();

        // SUPPRESION DU SUJET


        $reqUpdate = $conn->prepare("DELETE FROM sujet
                                     WHERE ID_SUJET = :subjectToRemove;");

        $reqUpdate->bindParam(':subjectToRemove', $subjectToRemove);

        $reqUpdate->execute();

        echo '<p> The subject has been removed please refresh the page </p>';
    }

    if (isset($_POST["messageList"])) {
        try {
            include 'DBConfig.php';

            $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
        } catch (PDOException $e) {
            die($e);
        }


        $messageToRemove = $_POST["messageList"];


        // SUPPRESSION DES MESSAGES A PARTIR D'UN SUJET PRECIS

        $reqUpdate = $conn->prepare("DELETE FROM message_sujet
                                     WHERE ID_MSG = :messageToRemove;");

        $reqUpdate->bindParam(':messageToRemove', $messageToRemove);

        $reqUpdate->execute();
        echo '<p> The Message has been removed please refresh the page </p>';
    }
}

function fillSubjectList()
{


    include 'DBConfig.php';
    try {
        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $stmt = $conn->prepare('SELECT LIBELLE_SUJET, ID_SUJET FROM sujet'); // RECUPERATION DES SUJETS
    $stmt->execute();
    echo "0 = pas bloqué, 1 = bloqué";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value=" . $row['ID_SUJET'] . ">" . $row['LIBELLE_SUJET'] . "</option>";  // AJOUT DE CHAQUE SUJET
    }
}


function fillMessageList()
{
    if (isset($_POST["nameSubjectMSG"])) {
        $nameSubjectMSG = $_POST["nameSubjectMSG"];
        include 'DBConfig.php';
        try {
            $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
        } catch (PDOException $e) {
            die($e);
        }
        $stmt = $conn->prepare('SELECT DESC_MSG, ID_SUJET, ID_MSG FROM message_sujet WHERE ID_SUJET = :nameSubjectMSG'); // RECUPERATION DES SUJETS
        $stmt->bindParam(':nameSubjectMSG', $nameSubjectMSG);
        $stmt->execute();
        echo "0 = pas bloqué, 1 = bloqué";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value=" . $row['ID_MSG'] . ">" . $row['DESC_MSG'] . "</option>";  // AJOUT DE CHAQUE SUJET
        }
    }
}
