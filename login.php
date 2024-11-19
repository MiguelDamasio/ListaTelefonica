<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php include 'linkstylesheet.php'; ?>
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
           $email = $_POST["email"];
           $password = $_POST["password"];
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {

                //https://www.php.net/manual/en/function.password-verify.php

                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = $user['full_name'];
                    $_SESSION["email"] = $user['email'];
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alert alert-danger'>Password não coincide!</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Email não coincide!</div>";
            }
        }
        ?>
        <h1>Faz Login para entrar na lista de contactos</h1>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="E-mail:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-success btn-primary">
            </div>
        </form>
        <div><p>Ainda não fizeste Sign up? <a href="registration.php">Sign up aqui!</a></p></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>