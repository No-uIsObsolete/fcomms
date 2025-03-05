<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);

    if (!checkLogin($user, $password)) {
        $errors['Invalid'] = "User or password is invalid";
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
        <title>Login</title>

    </head>
    <body>
        <main>
            <section class="LoginBox">
                <h2>FComms</h2>
                <section>
                    <form action="/login.php" method="post">
                        <p>
                            Login
                        </p>
                        <?php
                        if (isset($errors['Invalid'])) {echo $errors['Invalid']."<br>";}
                        ?>
                        <input type="text" placeholder="Email or Password" name="user"> <br>
                        <input type="Password" placeholder="Password" name="password"> <br>
                        <input type="checkbox" id="RememberCheck">
                        <label for="RememberCheck">Remember Me</label> <br>
                        <a href="reset-password.php">Forgot my password</a> <br>
                        <a href="register.php">Register</a> <input type="submit" value="Sign in"> <br>
                    </form>
                </section>
            </section>
        </main>
    </body>
</html>