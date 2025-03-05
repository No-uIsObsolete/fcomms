<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function connect()
{
    return mysqli_connect("195.201.38.255", "afura_fcomms", ")Pc8p[fEpO+Qy:6*", "afura_fcomms");
}

function addUser($user, $password, $email, $firstname, $lastname, $telephone)
{
    $con = connect();
    $currentTime = date("Y-m-d H:i:s");
    $hashedPassword = hash('sha256', $password);


    $query = "INSERT INTO users (username, password, email, firstname, lastname, telephone,created_at,updated_at) values ('$user', '$hashedPassword', '$email', '$firstname', '$lastname', '$telephone', '$currentTime', '$currentTime')";
    $sql = mysqli_query($con, $query);
}

function sqlResult($query)
{
    $con = connect();
    $sql = mysqli_query($con, $query);
    $result = array();
    while ($row = $sql->fetch_assoc()) {
        $result[] = $row;
    }
    return $result;
}

function checkUsername($username)
{
    $con = connect();
    $query = "SELECT username FROM users WHERE username = '$username'";
    $sql = mysqli_query($con, $query);
    if ($sql->num_rows > 0) {
        while ($row = $sql->fetch_assoc()) {
            if ($row['username'] == $username) {
                return false;
            }
        }
    } else {
        return true;
    }

}

function checkEmail($email)
{
    $con = connect();
    $query = "SELECT email FROM users WHERE email = '$email'";
    $sql = mysqli_query($con, $query);
    if ($sql->num_rows > 0) {
        while ($row = $sql->fetch_assoc()) {
            if ($row['email'] == $email) {
                return false;
            }
        }
    } else {
        return true;
    }
}

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
    } else {
        $find = '+';
        $pos = strpos($telephone, $find);
        if ($pos !== false) {
            if (strlen($telephone) > 7 && strlen($telephone) <= 15) {
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

function uppercaseCharactersValidation($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    if (!$uppercase) {
        return false;
    } else {
        return true;
    }
}

function lowercaseCharactersValidation($password)
{
    $lowercase = preg_match('@[a-z]@', $password);
    if (!$lowercase) {
        return false;
    } else {
        return true;
    }
}

function numbersValidation($password)
{
    $number = preg_match('@[0-9]@', $password);
    if (!$number) {
        return false;
    } else {
        return true;
    }
}

function specialCharactersValidation($password)
{
    $specialChars = '!@#$%^&*()-_=+[{]};:\'",<.>/?\\|';
    if (strpbrk($password, $specialChars) === false) {
        return false;
    } else {
        return true;
    }
}


function passwordValidation($password)
{
    if (strlen($password) > 7) {

        return true;

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


function checkLogin($user, $password)
{
    $con = connect();
    $hashedPassword = hash('sha256', $password);
    $query = "SELECT username, email, `password` FROM users where (username = '$user' or email = '$user') and password = '$hashedPassword'";
    $sql = mysqli_query($con, $query);
    if ($sql->num_rows > 0) {

        while ($row = $sql->fetch_assoc()) {
            $hash = $row['password'];
            if ($row['email'] == $user || $row['username'] == $user && password_verify($password, $hash) == true) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}




