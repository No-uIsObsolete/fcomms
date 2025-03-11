<?php
require 'src/functions.php';





if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $list = [];
    $result = $_GET['friendSearch'];

    $userid = $_SESSION['user']['id'];

    $list = searchUsers($result, $userid);
    //echo "<pre>";
    //var_dump($list);

    $pendingResult = searchPending($userid);




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
            <h3>Friends:</h3>
            <?php
            foreach ($list as $i=> $user) {
                if ($user['is_friend']==1) {
                    echo '<section>'.$user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='".$user['id']."' value='Unfriend' class='un-friend'> <br></section>";
                }
            }
            ?>
        </section>
        <section>
            <h3>Other users:</h3>
            <?php
            foreach ($list as $i=> $user) {
                if ($user['is_friend']==0) {
                    foreach ($pendingResult as $i=> $result) {
                        if ($result['pending']==0) {
                        echo '<section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Add friend' class='add-friend'> <br></section>";
                        }
                        else {
                            echo '<section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Request pending' class='blocked' disabled> <br></section>";
                        }
                    }
                }
            }
            ?>
        </section>
    </section>

</main>
<aside></aside>
<footer><script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".un-friend").click(function(){
                let obj = $(this)
            console.log($(this).attr('data-friend-id'))
                $.post("ajax-test.php",
                    {
                        friend_id: $(this).attr('data-friend-id'),
                        action: "remove_friend"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                            obj.parent().remove().alert('Success')

                        }
                    });
            });

            $(".add-friend").click(function(){
                let obj = $(this)
                console.log($(this).attr('data-friend-id'))
                $.post("ajax-test.php",
                    {
                        friend_id: $(this).attr('data-friend-id'),
                        action: "add_friend"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                           obj.attr({value: "Request Pending", class: "blocked", disabled})

                            alert('Success')

                        }
                    });
            });
        });
    </script></footer>

</body>
</html>
