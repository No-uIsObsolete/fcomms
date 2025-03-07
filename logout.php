<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
//$errors = [];

//header('Location: dashboard.php');


if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;

    session_unset();

    session_destroy();



}

    header('Location: login.php');




?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logging out</title>
    <link rel="stylesheet" href="/assets/css/index-style.css">
</head>
<body>
<header><h1>FComms</h1></header>
<main class="container">
    <section class="align">
        <h2 id="headerText">Logging out</h2>
    </section>
</main>
<footer></footer>

</script>
</body>
</html>