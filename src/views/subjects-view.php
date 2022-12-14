<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Liste des sujets dispo :</h1>
    <?php
    require __DIR__ . '/../controllers/subjects-controller.php';
    checkIfConnect();
    getAllSubjects(); // retourne la liste des sujets 

    ?>

    <form action="." method="post">
        <h2>Créé un sujet</h2>
        <p>Nom du sujet: <input type="text" name="subjectName" /> *
        <p><input value="Créé un sujet" type="submit" /></p>
    </form>
</body>

</html>