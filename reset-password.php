<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
$errors = [];
$alert = '';

if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;



    header('Location: index.php');

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];

    if (!emailExists($email)) {
        $errors['emailNotExist'] = "This email doesn't exist";
    }
    else {
        $alert = createToken($email);
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
    <title>Password Reset</title>
    <link rel="stylesheet" href="/assets/css/passreset-style.css">
</head>
<body>
<header><h1>FComms</h1></header>
<aside></aside>
    <main>
        <section class="passreset-box">
        <section class="passreset-box-header">
        <h2>FComms</h2>
        </section>
        <section class="passreset-box-content">
            <form action="/reset-password.php" method="post">
                <section>
                <p class="content-centered">
                    Forgot Password? <br>
                    Type in your email to receive an email with password reset authorization
                </p>

                <input type="email" placeholder="Email" class="input-centered" name="email"> <?php if (isset($errors['emailNotExist'])) {
                    echo $errors['emailNotExist'];
                } else {
                    echo $alert;
                }?><br>
                </section>
                <section class="content-bottom">
                <input type="submit" class="sign-button-reset" value="Send Email">
                 </section>
            </form>
        </section>
        </section>
    </main>
<aside></aside>
<footer></footer>
</body>
</html>