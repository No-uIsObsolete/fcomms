<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
//$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;

}
else {
    header('Location: index.php');
}




?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/assets/css/dashboard-style.css">

</head>
<body>

<header>
<h1>FComms</h1>
</header>
<aside class="social-box">
    <section class="containers">
        <h3>Friend list</h3>
    </section>
    <section class="containers">
        <h3>Groups</h3>
    </section>
</aside>
<main>
<section class="containers">
    <h3>Feed</h3>
</section>

</main>
<aside class="setting-box">
<section class="containers">
    <h3>Account Settings</h3>
    <a href="/logout.php">Logout</a>
</section>
</aside>
<footer>

</footer>
</body>
</html>