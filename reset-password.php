<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
$errors = [];
$alert = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];

    if (!emailExists($email)) {
        $errors['emailNotExist'] = "This email doesn't exist";
    }
    else {
        createToken($email);
        $alert['1'] = "Your request has been sent to your email";
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

</head>
<body>
<main>
    <section class="RegisterBox">
        <h2>FComms</h2>
        <section>
            <form action="/reset-password.php" method="post">
                <p>
                    Forgot Password?
                </p>
                Type email here: <br>
                <input type="email" placeholder="Email" name="email"> <?php if (isset($errors['emailNotExist'])) {
                    echo $errors['emailNotExist'];
                } else {
                    if (isset($alert['1'])) $alert['1'];
                }?><br>
                <input type="submit" value="Send Email">
            </form>
        </section>
    </section>
</main>
</body>
</html>