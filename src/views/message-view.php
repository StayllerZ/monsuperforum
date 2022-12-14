<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea'
        });
    </script>
</head>

<body>
    <?php
    require __DIR__ . '/../controllers/messages-controller.php';

    if (isset($_GET['subject']) && isset($_GET['id'])) {

        // Protection XSS : Utilisation de la fonction htmlspecialchars() pour convertir/encoder les caractères spéciaux saisie par l'utilisatuer.

        $subject = htmlspecialchars($_GET['subject'], ENT_QUOTES, 'UTF-8');
        $idsubject = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

        echo "<h1>Liste des messages pour le sujet " . $subject . " :</h1>";

        getAllMessagesByASubject($subject, $idsubject);
    } else {
        header('Location: ../../index.php');
    }
    ?>
    <form action=<?php echo "./" . basename($_SERVER['REQUEST_URI']); ?> method="post">
        <h2>Poster un message</h2>
        <textarea name="messageContent">Message</textarea>
        <p><input value="Poster" type="submit" /></p>
    </form>
</body>

</html>