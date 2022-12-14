<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration profile</title>
</head>

<body>
    <?php
    require '../controllers/profil-controller.php';
    ?>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <h1>Profil settings</h1>
        <p>Login: <input type="text" name="login" /> *
        <p><input type="file" name="image" accept="image/*" /></p>
        <button type="submit">Save informations</button>
    </form>
</body>

</html>