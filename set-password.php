<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
$errors = [];
$alert = '';
$token = $_GET['token'];
if (empty($token)) {
    header('Location: login.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];

    if (!passwordRepeatValidation($password, $passwordRepeat)) {
        $errors['password'] = "Password isn't the same";
    }
    if (!passwordValidation($password)) {
        $errors['passwordLength'] = "Password should be at least 8 characters";
    }
    if (!specialCharactersValidation($password)) {
        $errors['passwordSpecialCharacters'] = "Password should contain at least one special character";
    }
    if (!uppercaseCharactersValidation($password)) {
        $errors['passwordUppercase'] = "Password should contain at least one uppercase character";
    }
    if (!lowercaseCharactersValidation($password)) {
        $errors['passwordLowercase'] = "Password should contain at least one lowercase character";
    }
    if (!numbersValidation($password)) {
        $errors['passwordNumber'] = "Password should contain at least one number";
    }

    $alert = checkToken($password, $token);




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
            <form action="/set-password.php?token=<?php echo $token;?>" method="post">
                <section class="content-top">
                <p class="content-centered">
                    Password Reset
                </p>
                <input type="password" placeholder="New Password" class="input-centered" name="password"> <?php if (isset($errors['passwordLength'])){
                    echo $errors['passwordLength'];
                } else {
                    if (isset($errors['passwordLowercase'])){ echo $errors['passwordLowercase'];}
                    else {
                        if (isset($errors['passwordUppercase'])){ echo $errors['passwordUppercase'];}
                        else {
                            if (isset($errors['passwordNumber'])){ echo $errors['passwordNumber'];}
                            else {
                                if (isset($errors['passwordSpecialCharacters'])){ echo $errors['passwordSpecialCharacters'];}
                            }
                        }


                    }
                }?><br>
                <input type="password" placeholder="Repeat New Password" class="input-centered" name="passwordRepeat"> <br>
                <?php echo $alert ?>
                </section>
                <section class="content-bottom">
                    <input type="reset" class="reset-button" value="Reset"><input type="submit" class="sign-button" value="Reset Password">
                </section>
            </form>
        </section>
    </section>
</main>
<aside></aside>
<footer></footer>
</body>
</html>