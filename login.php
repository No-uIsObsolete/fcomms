<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
    //$errors = [];
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);




    $checkLogin = checkLogin($user, $password);

    if ($checkLogin = "Success") {
        $_SESSION['userid'] = getUserId($user, $password);
        $website = "/dashboard.php";
    }
    else
    {

        $website = "/login.php";
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
                        <form action="<?php echo $website; ?>" method="post">
                            <section class="content-top">
                            <p class="content-centered">
                                Login
                            </p>
                            <input type="text" placeholder="Email or Password" name="user" class="input-centered"> <br>
                            <input type="Password" placeholder="Password" name="password" class="input-centered"> <br>
                            <input type="checkbox" name="rememberCheck" class="input-checkbox"> <?php if (isset($checkLogin)) {echo $checkLogin;} ?>
                            <label for="rememberCheck" class="input-label">Remember Me</label> <br>
                            </section>
                            <section class="content-bottom">
                            New to FComms? <a href="register.php">Register</a> <br>
                            <a href="reset-password.php">Forgot my password</a>
                            <input type="submit" class="sign-button" value="Sign in"> <br>
                            </section>
                        </form>
                    </section>
            </section>
        </main>
    <aside></aside>
    <footer></footer>
    </body>
</html>