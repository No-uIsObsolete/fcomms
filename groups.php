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
            <h3>Groups:</h3>
            <?php
            foreach ($list as $i=> $group) {
                if ($group['member'] == 1) {
                    echo $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Leave Group' class='leave-group'> 
                    </section><br>";
                }
                elseif ($group['join_request'] == 1) {
                    echo $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Request pending' class='blocked' disabled> 
                    <input type='button' data-group-id='" . $group['id'] . "' value='Remove request' class='remove-join-request'></section><br>";
                }
                else {
                    echo $group['group_name'] . " <input type='button' data-group-id='" . $group['id'] . "' value='Join Group' class='join-group'> 
                    </section><br>";
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



        });
    </script></footer>

</body>
</html>
