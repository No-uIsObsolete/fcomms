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
            <h3>Search Results:</h3>
            <?php
            foreach ($list as $i=> $user) {
                if ($user['is_friend'] == 1) {
                    echo '<section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Unfriend' class='un-friend'> </section><br>";
                }
                elseif ($user['request_pending'] == 1) {
                    echo '<section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Request Pending' class='blocked' disabled> 
                    <input type='button' data-friend-id='" . $user['id'] . "' value='Remove request' class='remove-request'></section><br>";
                }
                else {

                    echo '<section>' . $user['firstname'] . " " . $user['lastname'] . " <input type='button' data-friend-id='" . $user['id'] . "' value='Add Friend' class='add-friend'> </section><br>";
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



        });
    </script></footer>

</body>
</html>
