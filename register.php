<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';


$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "The request is using the POST method <br>";
//var_dump($_POST);
//die;

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $telephoneCode = trim($_POST['telephone1']);
    $telephoneNumber = trim($_POST['telephone2']);
    $telephone = trim($telephoneCode . $telephoneNumber);
    $passwordRepeat = trim($_POST['passwordRepeat']);


    /*
    if (strlen($username) == 0 || strlen($password) == 0 || strlen($firstname) == 0 || strlen($lastname) == 0 || strlen($email) == 0) {
        return false;
    }
    else {
        if ($passwordRepeat == $password) {
            echo "Nazwa Użytkownika: $username <br> Imie: $firstname <br> Nazwisko: $lastname <br> Hasło: $password <br> Email: $email <br> Telefon: $telephone";
        }
    }*/

    if (!usernameValidation($username)) {
        $errors['username'] = "Username is invalid";
    }

    if (!emailValidation($email)) {
        $errors['email'] = "Email is invalid";
    }
    if (!telephoneValidation($telephone)) {
        $errors['telephone'] = "Telephone number is invalid";
    }
    if (!passwordRepeatValidation($password, $passwordRepeat)) {
        $errors['password'] = "Password isn't the same";
    }
    if (!mainValidation($username, $password, $email, $firstname, $lastname)) {
        $errors['main'] = "<p>Fill all the required fields</p>";
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

    if (!checkUsername($username)) {
        $errors['usernameInUse'] = "Username already exists";
    }

    if (!checkEmail($email)) {
        $errors['emailInUse'] = "Email already exists";
    }


        if (empty($errors))  {
            addUser($username, $password, $email, $firstname, $lastname, $telephone);
            header('Location: login.php');
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
    <title>Register</title>

    <link rel="stylesheet" href="/assets/css/register-style.css">


</head>
<body>
<header><h1>FComms</h1></header>
<aside></aside>
<main>
    <section class="register-box">
        <section class="register-box-header">
        <h2>FComms</h2>
        </section>
        <section  class="register-box-content">
            <form action="/register.php" method="post">
                <section class="content-top">
                <p class="content-centered">
                    Register
                </p>

                    <?php if (isset($errors['main'])){
                    echo $errors['main'];
                    } ?>
                <input type="text" placeholder="Username" name="username" class="input-centered" value="<?php echo $_POST['username']??'';?>">*
                    <?php if (isset($errors['username'])){
                        echo $errors['username'];
                    }
                    else {
                        if (isset($errors['usernameInUse'])){echo $errors['usernameInUse'];}
                    }?><br>

                <input type="text" placeholder="Firstname" name="firstname" class="input-centered" value="<?php echo $_POST['firstname']??'';?>">* <br>
                <input type="text" placeholder="Lastname" name="lastname" class="input-centered" value="<?php echo $_POST['lastname']??'';?>">* <br>
                <input type="email" placeholder="Email" name="email" class="input-centered" value="<?php echo $_POST['email']??'';?>">*
                    <?php if (isset($errors['email'])){
                        echo $errors['email'];
                    }
                    else {
                        if (isset($errors['emailInUse'])){echo $errors['emailInUse'];}
                    }?> <br>
                <input class="telephone-code" type="text" placeholder="Code" name="telephone1" value="<?php echo $_POST['telephone1']??'';?>"><input
                        type="tel" placeholder="Telephone Number" class="telephone-number" name="telephone2" value="<?php echo $_POST['telephone2']??'';?>">
                    <?php if (isset($errors['telephone'])){
                        echo $errors['telephone'];
                    }?><br>
                <input type="password" placeholder="Password" class="input-centered" name="password" value="<?php echo $_POST['password']??'';?>">* <?php if (isset($errors['passwordLength'])){
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
                    }?> <br>

                <input type="password" placeholder="Repeat Password" class="input-centered" name="passwordRepeat" value="<?php echo $_POST['passwordRepeat']??'';?>">*
                    <?php if (isset($errors['password'])){
                        echo $errors['password'];
                    } ?> <br>
                    </section>
                    <section class="content-bottom">
                <input type="submit" class="sign-button" value="Sign up">
                    </section>
        </section>
            </form>
    </section>
    </section>

</main>
<aside></aside>
<footer></footer>
</body>
</html>