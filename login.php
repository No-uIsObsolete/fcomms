<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
    //$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;



    header('Location: index.php');

}
//phpinfo(); die;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);

    $checkbox = trim($_POST['rememberCheck']);




    $checkLogin = checkLogin($user, $password);

    if ($checkLogin = "Success") {
        //if (!empty($checkbox)) {
        //    setcookie("user", $user.", ".$password, time() + (86400 * 30), "/");
        //}
        $_SESSION['user'] = getUser($user, $password);
            header('Location: index.php');
        }

}



?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
       <link rel="stylesheet" href="/assets/css/login-style.css">
        <title>Login</title>

    </head>
    <body>
    <header><h1>FComms</h1></header>
    <aside></aside>
        <main>
            <section class="login-box">
                <section class="login-box-header">
                <h2>FComms</h2>
                </section>
                    <section class="login-box-content">
                        <form action="/login.php" method="post">
                            <section class="content-top">
                            <p class="content-centered">
                                Login
                            </p>
                            <input type="text" placeholder="Email or Password" name="user" class="input-centered"> <br>
                            <input type="Password" placeholder="Password" name="password" class="input-centered"> <br>
                            <?php if (isset($checkLogin)) {echo $checkLogin;} ?> <br>
                            <input type="checkbox" name="rememberCheck[]" class="input-checkbox" value="rememberMe">
                            <label for="rememberCheck" class="input-label">Remember Me</label> <br>
                            </section>
                            <section class="content-bottom">
                            New to FComms? <a href="register.php">Register</a> <br>
                            <a href="reset-password.php">Forgot my password</a>
                                <input type="reset" class="reset-button" value="Reset"><input type="submit" class="sign-button" value="Sign in"> <br>
                            </section>
                        </form>
                    </section>
            </section>
        </main>
    <aside></aside>
    <footer></footer>
    </body>
</html>