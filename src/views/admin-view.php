<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if (!isset($_COOKIE["PHPSESSID"])) {
        header('Location: ./../../index.php');
    } else {
        require '../controllers/admin-page-controller.php';
        session_start();
        if ($_SESSION["User"] == "testtest") {
            displayUser();
            changeBlockState();
        } else {
            header('Location: ./../../index.php');
        }
    }

    ?>
    <div>
        <form action="" method="post">
            <h3>Block/unblock User : </h3>
            <label for="name">User : </label>
            <input type="text" name="name" id="name" required>

            <select name="block" id="block">
                <option value="0">Pas bloqué</option>
                <option value="1">Bloqué</option>
            </select>

            <input type="submit" value="Submit">
        </form>
    </div>

    <div>
        <h3>Remove Subject : </h3>
        <form action="" method="post">
            <label for="nameSubject">Subject : </label>
            <select name="nameSubject" id="nameSubject">
                <?php
                fillSubjectList();
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>
    </div>

    <div>
        <h3>Remove Message from Subject : </h3>
        <form action="" method="post">
            <label for="nameSubjectMSG">Subject : </label>
            <select name="nameSubjectMSG" id="nameSubjectMSG">
                <?php
                fillSubjectList();
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>
        <form action="" method="post">
            <label for="nameSubjectMSG">Message : </label>
            <select name="messageList" id="messageList">
                <?php
                fillMessageList();
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>


    </div>

</body>

</html>