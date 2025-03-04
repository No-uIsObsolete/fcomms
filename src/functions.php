<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function connect()
{
    return mysqli_connect("195.201.38.255", "afura_fcomms", ")Pc8p[fEpO+Qy:6*", "afura_fcomms");
}

function addUser(String $user, String $password, String $email, String $firstname, String $lastname, Int $telephone)
{
    $con = connect();
    $currentTime = date("Y-m-d H:i:s");
    $hashedPassword  = password_hash($password, PASSWORD_BCRYPT);



    $query = "INSERT INTO users (username, password, email, firstname, lastname, telephone,created_at,updated_at) values ('$user', '$hashedPassword', '$email', '$firstname', '$lastname', '$telephone', '$currentTime', '$currentTime')";
    $sql = mysqli_query($con, $query);
}

