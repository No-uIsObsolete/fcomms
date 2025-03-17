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

    $list2 = friendRequestGet($userid);

    $friend_requests = [];
    foreach ($list2 as $friend) {
        $friend_requests[$friend['from_user_id']] = $friend;
    }


//    echo "<pre>";
//    var_dump($list);
//    var_dump($list2);
//    var_dump($friend_requests);
//    die;





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
    <link rel="stylesheet" href="assets/css/search-style.css">
</head>
<body>
<header><h1>FComms</h1></header>
<aside><section class="containers">
        <form method="get" action="users.php">
            <section class="search-bar"><label>Search Users:</label> <input class="search-box" type="search" placeholder="Search" name="friendSearch"><input class="search-buttons" type="submit" value="Search"></section>
        </form>
        <h3>Pending Requests:</h3> <hr>
        <?php
        if ($list2 != "No requests found") {
            foreach ($list2 as $i => $user) {
                if ($user['request_status'] == 1) {
                    echo '<br><section friend-request-from = "'.$user['from_user_id'].'">' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['from_user_id'] . "' value='Accept' class='request-accept'>
 <input type='button' data-friend-id='" . $user['from_user_id'] . "' value='Decline' class='request-decline'></section><br><hr>";

                }
            }
        }
        else {
            echo $list2;
        }

        ?>

    </section></aside>
<main>
    <section class="containers">
    <h3>Search Results:</h3> <hr>
    <?php
    foreach ($list as $i=> $user) {
        if ($user['is_friend'] == 1) {
            echo '<br><section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Unfriend' class='un-friend'> </section><br><hr>";
        }
        elseif ($user['request_status'] == 1 && $user['request_to'] == 1) {
            echo '<br><section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Request Pending' class='blocked' disabled> 
                    <input type='button' data-friend-id='" . $user['id'] . "' value='Remove request' class='remove-request'></section><br><hr>";
        }
        elseif (isset($friend_requests[$user['id']])) {
//                elseif ($user['request_status'] == 1 && $user['friend_request_from'] == 1) {
            echo '<br><section friend-request-from = "'.$user['id'].'">' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Accept' class='request-accept'>
                     <input type='button' data-friend-id='" . $user['id'] . "' value='Decline' class='request-decline'></section><br><hr>";
        }
        else {

            echo '<br><section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Add Friend' class='add-friend'> </section><br><hr>";
        }
    }
    ?>
    </section>
</main>
<aside><section class="containers">

    </section></aside>
<footer><script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '.un-friend', function(){
                let obj = $(this)
            console.log($(this).attr('data-friend-id'))
                $.post("ajax.php",
                    {
                        friend_id: $(this).attr('data-friend-id'),
                        action: "remove_friend"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                            obj.attr('value', "Add Friend")
                            obj.attr('class', "add-friend")


                            alert('Success')

                        }
                    });
            });

            $(document).on('click', '.add-friend', function(){
                let obj = $(this)
                console.log($(this).attr('data-friend-id'))
                $.post("ajax.php",
                    {
                        friend_id: $(this).attr('data-friend-id'),
                        action: "add_friend"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                           obj.attr('value', "Request Pending")
                           obj.attr('class', "blocked")
                           obj.attr('disabled', true)
                           obj.after(" <input type='button' data-friend-id='"+$(this).attr('data-friend-id')+"' value='Remove Request' class='remove-request'>")

                            alert('Success')

                        }
                    });
            });

            $(document).on('click', '.remove-request', function(){
                let obj = $(this)
                console.log($(this).attr('data-friend-id'))
                $.post("ajax.php",
                    {
                        friend_id: $(this).attr('data-friend-id'),
                        action: "remove_request"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                            obj.prev('.blocked').attr('value', "Add Friend")
                            obj.prev('.blocked').removeAttr("disabled")
                            obj.prev('.blocked').attr('class', "add-friend")

                            obj.remove()

                            alert('Success')

                        }
                    });
            });
            $(document).on('click', '.request-accept', function(){
                let obj = $(this)
                console.log($(this).attr('data-friend-id'))
                let userId = $(this).parent().attr('friend-request-from')

                $.post("ajax.php",
                    {
                        friend_request_id: $(this).attr('data-friend-id'),
                        accept_or_decline: 1,
                        action: "request_process"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {

                            $('section[friend-request-from="'+userId+'"]').children('.request-accept').attr('value', "Unfriend").attr('class', "un-friend")
                            $('section[friend-request-from="'+userId+'"]').children('.request-decline').remove()


                            alert('Success')

                        }
                    });
            });
            $(document).on('click', '.request-decline', function(){
                let obj = $(this)
                console.log($(this).attr('data-friend-id'))
                let userId = $(this).parent().attr('friend-request-from')

                $.post("ajax.php",
                    {
                        friend_request_id: $(this).attr('data-friend-id'),
                        accept_or_decline: 0,
                        action: "request_process"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {


                            $('section[friend-request-from="'+userId+'"]').children('.request-accept').attr('value', "Add Friend").attr('class', "add-friend")
                            $('section[friend-request-from="'+userId+'"]').children('.request-decline').remove()

                            alert('Success')

                        }
                    });
            });




        });
    </script></footer>

</body>
</html>
