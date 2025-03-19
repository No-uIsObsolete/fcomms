<?php
require 'src/functions.php';
if (!isset($_GET['friend'])) {
    header('Location: index.php');
}
$friendId = $_GET['friend'];
//$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $userid = $_SESSION['user']['id'];
    $userFirstname = $_SESSION['user']['firstname'];
    $userLastname = $_SESSION['user']['lastname'];
    $friendResult = getFriends($userid);
    if (!empty(checkFriendChat($userid, $friendId))) {
        $currentChat = checkFriendChat($userid, $friendId);
    } else {
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST['postTextArea'];
    $friendFormId = $_POST['friend_id'];

    header('Location: chat.php?friend=' . $friendFormId);
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
    <link rel="stylesheet" href="/assets/css/chat-style.css">

</head>
<body>

<header>
    <h1 class="logo">FComms</h1>
    <section class="user-logged-in"><?php echo $userFirstname . " " . $userLastname ?></section>
    <a class="logout-button" href="/logout.php">Logout</a>
</header>
<aside>
    <section class="containers">
        <h3>Friend list</h3>
        <hr>
        <section>
            <ul class='friend-list-ul'>
                <?php
                //var_dump($friendResult);
                if (isset($friendResult)) {
                    $row = 0;
                    foreach ($friendResult as $friend) {
                        echo "
            
            
                <li class='friend-list-li'>
                <a class='lists' href='chat.php?friend=" . $friend['friend_user_id'] . "'>  <img src=" . $friend['profile_picture'] . " alt='profile_picture' class='pfp'> <b class='friend-list-a'>" . $friend['firstname'] . " " . $friend['lastname'] . "</b> </a>
                </li>
            
            <hr>
            ";

                    }
                }
                ?>
            </ul>
        </section>
    </section>

</aside>
<main>
    <section class="containers">
        <h3>Chat with <?php

            echo $currentChat[0]['firstname'] . " " . $currentChat[0]['lastname'];

            ?></h3> <br>
        <section>
            <hr>
            <!--            <form method="post" action="/chat.php?friend=--><?php //= $friendId; ?><!--">-->

            <section class="textarea-section"><input type="hidden" name="friend_id" value="<?= $friendId; ?>"> <textarea
                        name="postTextArea" placeholder="Your message here" cols="129" rows="3"></textarea><input
                        type="submit" value="-->" class="post-button" submit="1"></section>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($postData) && !empty($postData)) {

                    header('Location: chat.php?friend=' . $friendId);
                } else {
                    echo "Post is empty";
                }
            }
            ?>

            <!--            </form>-->
            <hr>

            <section id="chat_texts"></section>
            <!--            --><?php
            //            foreach (getChatMessages($userid, $friendId) as $i => $message) {
            //
            //echo <<<EOL
            //<br>{$message['firstname']} {$message['lastname']} {$message['read_at']}
            //                <br><sup>{$message['created_at']}</sup>
            //                <br>{$message['message_content']}<br><br>
            //EOL;
            //
            //
            //
            //            }
//
            //
            //
            //
            //
            //            ?>

        </section>
    </section>

</main>
<aside>
    <section class="containers"></section>
</aside>
<footer>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/mqtt.min.js"></script>
    <script type="text/javascript">
        const url = 'wss://mqtt.kgtech.pl:8888'
        const options = {
            clean: true,
            connectTimeout: 4000
        }
        const client = mqtt.connect(url, options)
        $(document).ready(function () {
            client.on('connect', function () {
                console.log('Connected')
                // Subscribe to a topic
                client.subscribe('fcomms/<?=$friendId ?>/<?=$userid ?>', function (err) {
                    if (!err) {
                        // Publish a message to a topic
                        // client.publish('test', 'Hello mqtt')
                    }
                })
                client.on('message', function (topic, payload, packet) {
                    // Payload is Buffer
                    console.log(`Topic: ${topic}, Message: ${payload.toString()}, QoS: ${packet.qos}`)
                    reloadChat()
                })
            })



            $(document).on('click', 'input[submit]', function () {

                let textarea = $('textarea[name="postTextArea"]').val()
                $.post("ajax.php",
                    {

                        from_user_id: <?= $userid ?>,
                        to_user_id: <?= $friendId ?>,
                        message: textarea,
                        action: 'sendMessage'
                    },function (data) {
                        let topic = 'fcomms/<?=$userid ?>/<?=$friendId ?>'
                        client.publish(topic, textarea)
                        reloadChat()

                    });


            });
            reloadChat()
        });
        function reloadChat() {
            $.post("ajax.php",
                {

                    from_user_id: <?= $userid ?>,
                    to_user_id: <?= $friendId ?>,
                    action: 'getChat'
                },
                function (data) {
                    console.log(data);
                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                        let content = ""
                        // let reactions = JSON.parse(data)
                        // console.log(reactions)
                        $.each(data.data, function (index, message) {
                            content += '<br>' + message.firstname + ' ' + message.lastname + ' <br><sup>' +
                                ''+message.created_at+'  </sup> <br>' +message.message_content +'<br><br>'


                        })
                    let messages = $('section[id="chat_texts"]')
                    messages.html(content)
                }
        });
        }

    </script>
</footer>
</body>
</html>