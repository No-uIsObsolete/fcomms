<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function usernameValidation($username)
{
    if (str_contains($username, ' ')) {
        return false;
    } else {
        return true;
    }
}

function emailValidation($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }

}

function telephoneValidation($telephone)
{
    if ($telephone == "") {
        return true;
    }
    else {
    $telephone;
    $find = '+';
    $pos = strpos($telephone, $find);
    if ($pos !== false) {
        if  (strlen($telephone) > 7 && strlen($telephone) <= 15)  {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    }

    /*$pattern = "/^\+?\d{1,4}?[-.\s]?(\(?\d{1,3}?\)?[-.\s]?)?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/";
    if (preg_match($pattern, $telephone)) {
        return true;
    } else {
        return false;
    }*/

}


function passwordValidation($password)
{
    if ($password >= 8 ) {

    } else {
        return false;
    }
}


function passwordRepeatValidation($password, $passwordRepeat)
{
    if ($passwordRepeat != $password) {
        return false;
    } else {
        return true;
    }
}

function mainValidation($username, $password, $email, $firstname, $lastname)
{
    if (strlen($username) == 0 || strlen($password) == 0 || strlen($firstname) == 0 || strlen($lastname) == 0 || strlen($email) == 0) {
        return false;
    } else {
        return true;
    }
}


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "The request is using the POST method <br>";
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
    if (!mainValidation($username, $password, $email, $telephone, $firstname, $lastname)) {
        $errors['main'] = "<p>Fill all the required fields</p>";
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

    <link rel="stylesheet" href="/assets/css/fcomms-register.css">


</head>
<body>
<main>
    <section class="RegisterBox">
        <h2>FComms</h2>
        <section>
            <form action="/register.php" method="post">
                <p>
                    Register
                </p>
                <section>
                    <?php if (isset($errors['main'])){
                    echo $errors['main'];
                    } ?>
                <input type="text" placeholder="Username" name="username" value="<?php echo $_POST['username']??'';?>">*
                    <?php if (isset($errors['username'])){
                        echo $errors['username'];
                    }?><br>
                <input type="text" placeholder="Firstname" name="firstname" value="<?php echo $_POST['firstname']??'';?>">* <br>
                <input type="text" placeholder="Lastname" name="lastname" value="<?php echo $_POST['lastname']??'';?>">* <br>
                <input type="email" placeholder="Email" name="email" value="<?php echo $_POST['email']??'';?>">*
                    <?php if (isset($errors['email'])){
                        echo $errors['email'];
                    }?> <br>
                <input class="TelephoneCode" type="text" placeholder="Code" name="telephone1" value="<?php echo $_POST['telephone1']??'';?>"><input
                        type="tel" placeholder="Telephone Number" class="TelephoneNumber" name="telephone2" value="<?php echo $_POST['telephone2']??'';?>">
                    <?php if (isset($errors['telephone'])){
                        echo $errors['telephone'];
                    }?><br>
                <input type="password" placeholder="Password" name="password" value="<?php echo $_POST['password']??'';?>">* <br>
                <input type="password" placeholder="Repeat Password" name="passwordRepeat" value="<?php echo $_POST['passwordRepeat']??'';?>">*
                    <?php if (isset($errors['password'])){
                        echo $errors['password'];
                    } ?> <br>
                <input type="submit" value="Sign up">
                </section>
            </form>

        </section>
    </section>
</main>
</body>
</html>