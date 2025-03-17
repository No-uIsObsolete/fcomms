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

    $grouplist = getUserGroups($userid);

    $invite_requests = [];
    foreach ($grouplist as $invite) {
        $invite_requests[$invite['from_user_id']] = $invite;
    }
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
<aside class="group-box"><section class="containers">
        <form form method="get" action="groups.php">
            <section class="search-bar"><label>Search Groups:</label> <input class="search-box" type="search" placeholder="Search" name="groupSearch"><input class="search-buttons" type="submit" value="Search"></section>
            <h3>Search Results:</h3> <hr>

        </form>
    </section> </aside>
<main>
    <section class="containers"><?php
        foreach ($list as $i=> $group) {
            if ($group['member'] == 1) {
                echo '<br><section>'. $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Leave Group' class='leave-group'> 
                    </section><br><hr>";
            }
            elseif ($group['join_request'] == 1) {
                echo '<br><section>'. $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Request pending' class='blocked' disabled> 
                    <input type='button' data-group-id='" . $group['id'] . "' value='Remove request' class='remove-join-request'></section><br><hr>";
            }
            elseif (isset($invite_requests[$group['id']])) {
                echo '<br><section>'. $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Accept Invite' class='accept-invite'> 
                    <input type='button' data-group-id='" . $group['id'] . "' value='Decline Invite' class='decline-invite'></section><br><hr>";
            }
            else {
                echo '<br><section>'. $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Join Group' class='join-group'> 
                    </section><br><hr>";
            }
        }
        ?></section>
</main>
<aside> <section class="containers">

    </section></aside>
<footer><script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '.leave-group', function(){
                let obj = $(this)
                console.log($(this).attr('data-group-id'))
                $.post("ajax.php",
                    {
                        group_id: $(this).attr('data-group-id'),
                        action: "leave_group"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                            obj.attr('value', "Join Group")
                            obj.attr('class', "join-group")


                            alert('Success')

                        }
                    });
            });

            $(document).on('click', '.join-group', function(){
                let obj = $(this)
                console.log($(this).attr('data-group-id'))
                $.post("ajax.php",
                    {
                        group_id: $(this).attr('data-group-id'),
                        action: "join_group"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                            obj.attr('value', "Request Pending")
                            obj.attr('class', "blocked")
                            obj.attr('disabled', true)
                            obj.after(" <input type='button' data-group-id='"+$(this).attr('data-group-id')+"' value='Remove request' class='remove-join-request'>")

                            alert('Success')

                        }
                    });
            });

            $(document).on('click', '.remove-join-request', function(){
                let obj = $(this)
                console.log($(this).attr('data-group-id'))
                $.post("ajax.php",
                    {
                        group_id: $(this).attr('data-group-id'),
                        action: "remove_join_request"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {
                            obj.prev('.blocked').attr('value', "Join Group")
                            obj.prev('.blocked').removeAttr("disabled")
                            obj.prev('.blocked').attr('class', "join-group")

                            obj.remove()

                            alert('Success')

                        }
                    });
            });
            $(document).on('click', '.invite-request-accept', function(){
                let obj = $(this)
                console.log($(this).attr('data-group-id'))
                $.post("ajax.php",
                    {
                        invite_request_id: $(this).attr('data-group-id'),
                        accept_or_decline: 1,
                        action: "request_process"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {


                            obj.prev().remove()

                            alert('Success')

                        }
                    });
            });
            $(document).on('click', '.invite-request-decline', function(){
                let obj = $(this)
                console.log($(this).attr('data-group-id'))
                $.post("ajax.php",
                    {
                        invite_request_id: $(this).attr('data-group-id'),
                        accept_or_decline: 0,
                        action: "request_process"
                    },
                    function(data){
                        console.log(data.status);
                        if (typeof data.status !== 'undefined' && data.status === 'success')
                        {


                            obj.prev().remove()

                            alert('Success')

                        }
                    });
            });


        });
    </script></footer>

</body>
</html>
