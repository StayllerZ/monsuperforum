<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<header>
    <h1>Mon Super Forum</h1>
</header>

<body>
    <div>
        <h2>Connection</h2>
        <?php
        require '../controllers/user-controller.php';
        alreadyLoginCheck();
        checkConnection();

        ?>
        <form action="" method="post">
            <label for="name">Login : </label>
            <input type="text" name="name" id="name" required>
            <label for="pass">Password :</label>
            <input type="password" id="password" name="password" minlength="8" required>
            <input type="submit" value="Envoyer le formulaire">
        </form>
        <a href="register-view.php">register</a>
    </div>
</body>

</html>