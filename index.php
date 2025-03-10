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



    $website = "http://fcomms.website/dashboard.php";

}
else {

    $website = "http://fcomms.website/login.php";
}



?>



<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Checking</title>
        <link rel="stylesheet" href="/assets/css/index-style.css">
    </head>
    <body>
    <header><h1>FComms</h1></header>
    <main class="container">
        <section class="align">
            <h2 id="headerText">Checking</h2>
        <section class="background-loader">
    <section class="loader">
        <section class="circle"></section>
    </section>
        </section>
        </section>
    </main>
    <footer></footer>
    <script>



window.addEventListener("load", function () {
    setTimeout(function(){
        location.href = '<?php echo $website; ?>';
    }, 8000);
    setTimeout(function(){

        document.title = 'Checking.';
        document.getElementById('headerText').innerHTML = 'Checking.';
    }, 2000);
    setTimeout(function(){
        document.title = 'Checking.';
        document.getElementById('headerText').innerHTML = 'Checking..';
    }, 3000);
    setTimeout(function(){

        document.title = 'Checking...';
        document.getElementById('headerText').innerHTML = 'Checking...';
    }, 4000);
    setTimeout(function(){

        document.title = 'Redirecting';
        document.getElementById('headerText').innerHTML = 'Redirecting';
    }, 5000);
    setTimeout(function(){

        document.title = 'Redirecting.';
        document.getElementById('headerText').innerHTML = 'Redirecting.';
    }, 6000);
    setTimeout(function(){

        document.title = 'Redirecting..';
        document.getElementById('headerText').innerHTML = 'Redirecting..';
    }, 7000);
    setTimeout(function(){

        document.title = 'Redirecting...';
        document.getElementById('headerText').innerHTML = 'Redirecting...';
    }, 8000);
})
    </script>
    </body>
</html>
