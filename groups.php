<?php
require 'src/functions.php';





if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $list = [];
    $result = $_GET['groupSearch'];

    $userid = $_SESSION['user']['id'];

    $list = searchGroups($result, $userid);
    //echo "<pre>";
    //var_dump($list);




}
else {
    header('Location: index.php');
}








?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Search</title>
</head>
<body>
<header><h1>FComms</h1></header>
<aside></aside>
<main>
    <section>
        <section>
            <h2>FComms</h2>
        </section>
        <section>
            <h3>Joined groups:</h3>
            <?php
            foreach ($list as $i=> $group) {
                if ($group['is_member']==1) {
                    echo $group['group_name'] . "<br>";
                }
            }
            ?>
        </section>
        <section>
            <h3>Other groups:</h3>
            <?php
            foreach ($list as $i=> $group) {
                if ($group['is_member']==0) {
                    echo $group['group_name'] . "<br>";
                }
            }
            ?>
        </section>
    </section>

</main>
<aside></aside>
<footer><script src="assets/js/jquery.js"></script>
    <script type="text/javascript">

    </script></footer>

</body>
</html>
