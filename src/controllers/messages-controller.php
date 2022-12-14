<?php
function sanitizing($string) // Fonction d'assainissement d'une la chaîne HTML (qui vas être l'entrée de l'utilisateur pour le contenu du message)
{

    $pattern = array(
        "/\[url\](.*?)\[\/url\]/",
        "/\[img\](.*?)\[\/img\]/",
        "/\[img\=(.*?)\](.*?)\[\/img\]/",
        "/\[url\=(.*?)\](.*?)\[\/url\]/",
        "/\[red\](.*?)\[\/red\]/",
        "/\[b\](.*?)\[\/b\]/",
        "/\[h(.*?)\](.*?)\[\/h(.*?)\]/",
        "/\[p\](.*?)\[\/p\]/",
        "/\[php\](.*?)\[\/php\]/is"
    );

    $replacement = array(
        '<a href="\\1">\\1</a>',
        '<img alt="" src="\\1"/>',
        '<img alt="" class="\\1" src="\\2"/>',
        '<a rel="nofollow" target="_blank" href="\\1">\\2</a>',
        '<span style="color:#ff0000;">\\1</span>',
        '<span style="font-weight:bold;">\\1</span>',
        '<h\\1>\\2</h\\3>',
        '<p>\\1</p>',
        '<pre><code class="php">\\1</code></pre>'
    );

    $string = preg_replace($pattern, $replacement, $string);

    $string = nl2br($string);

    return $string;
}

if ($_POST && isset($_POST['messageContent']) && strlen($_POST['messageContent']) > 0) {

    // Protection XSS : Utilisation de la fonction htmlspecialchars() pour convertir/encoder les caractères spéciaux saisie par l'utilisatuer.

    $message = sanitizing($_POST['messageContent']);
    $idsubject = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
    insertMessage($message, $idsubject);
}


function getAllMessagesByASubject($subject)
{

    include 'DBConfig.php';

    try {
        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $reqGetMessages = $conn->prepare( // Utilisation de requêtes préparé pour éviter les injection SQL.
        "SELECT message_sujet.DESC_MSG, message_sujet.ID_USER, utilisateur.LOGIN_USER, utilisateur.ID_USER, utilisateur.IMG_USER
        FROM message_sujet
        INNER JOIN sujet ON message_sujet.ID_SUJET = sujet.ID_SUJET
        INNER JOIN utilisateur ON utilisateur.ID_USER =  message_sujet.ID_USER
        WHERE sujet.LIBELLE_SUJET = :subject"
    );
    $reqGetMessages->bindParam(':subject', $subject);
    $reqGetMessages->execute();
    $index = 1;
    while ($row = $reqGetMessages->fetch(PDO::FETCH_ASSOC)) {
        echo "<div>" . $index . " - " .  "Message posté par l'utilisateur <strong>" . $row["LOGIN_USER"] . " :</strong></div>";
        echo "<img width=\"75\" height=\"75\" src=../../images/" . $row['IMG_USER'] . " >";
        echo "<div><p>" . $row["DESC_MSG"] . "</p></div><br>";
        $index++;
    }
}


function insertMessage($message, $idsubject)
{
    try {
        include 'DBConfig.php';

        $conn = new PDO($connectionString[0], $connectionString[1], $connectionString[2]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1);
    } catch (PDOException $e) {
        die($e);
    }
    $reqInsertSubject = $conn->prepare("INSERT INTO message_sujet (DAT_CREA_MSG, DESC_MSG, ID_SUJET, ID_USER)
                                        VALUES (:dateCreation, :messageContent, :idSubject, :idUser);");

    $todayDate = date("d-m-Y H:i:s");
    session_start();
    $idUser = $_SESSION["Id"];

    $reqInsertSubject->bindParam(':dateCreation', $todayDate);
    $reqInsertSubject->bindParam(':messageContent', $message);
    $reqInsertSubject->bindParam(':idSubject', $idsubject);
    $reqInsertSubject->bindParam(':idUser', $idUser);


    $reqInsertSubject->execute();
}
