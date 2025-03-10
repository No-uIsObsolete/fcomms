<?php
require 'src/functions.php';





if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $friends = [];
    $strangers = [];
    $result = $_GET['friendSearch'];

    $userid = $_SESSION['user']['id'];

    $friendResult = getFriends($userid);

    $list = searchUsers($result, $userid);

    $row = 0;
    $row2 = 0;
        while ($row < count($friendResult)) {
            while ($row2 < count($list)) {
                if ($friendResult[$row]['friend_user_id'] == $list[$row2]['id']) {
                    $friends = array($list[$row2]);
                }
                else {
                    $strangers = array($list[$row2]);
                }
                $row2++;
            }
            $row++;
        }

        echo "<pre>";
        echo "friends: ";
        var_dump($friends);
        echo "strangers: ";
        var_dump($strangers);


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
    <title>Document</title>
</head>
<body>

</body>
</html>
