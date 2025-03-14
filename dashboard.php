<?php
require 'src/functions.php';
//$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $userid = $_SESSION['user']['id'];
    $friendResult = getFriends($userid);
    $groupResult = getGroups($userid);
    $postResult = getPosts($userid);

} else {
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST['postTextArea'];
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
        <form method="get" action="users.php">
            <section class="search-bar"><label>Add more:</label> <input class="search-box" type="text" placeholder="Search" name="friendSearch"><input class="search-buttons" type="submit" value="Search"></section>
        </form>
        <hr>
        <section>
            <ul class='friend-list-ul'>
                <?php
                //var_dump($friendResult);
                if (isset($friendResult)) {
                    $row = 0;
                    while ($row < count($friendResult)) {
                        echo "
            
            
                <li class='friend-list-li'>
                <a>  <img src=" . $friendResult[$row]['profile_picture'] . " alt='profile_picture' class='pfp'> <b class='friend-list-a'>" . $friendResult[$row]['firstname'] . " " . $friendResult[$row]['lastname'] . "</b> </a>
                </li>
            
            <hr>
            ";
                        $row++;
                    }
                }
                ?>
            </ul>
        </section>
    </section>
    <section class="containers" id="group-container">
        <h3>Groups</h3>
        <form form method="get" action="groups.php">
            <section class="search-bar"><label>Join more:</label> <input class="search-box" type="text" placeholder="Search" name="groupSearch"><input class="search-buttons" type="submit" value="Search"></section>
        </form>
        <hr>
        <section>
            <ul class='group-ul'>
                <?php
                //var_dump($groupResult);
                if (isset($groupResult)) {
                    $row = 0;
                    while ($row < count($groupResult)) {
                        echo "
            
            
                <li class='group-li'>
                <a>" . $groupResult[$row]['group_name'] . " </a>
                </li>
            
            <hr>
            ";
                        $row++;
                    }
                }

                ?>
            </ul>
        </section>
    </section>
</aside>
<main>
    <section class="containers">
        <h3>Feed</h3> <br>
        <section>
            <hr>
            <form method="post" action="/dashboard.php">
                <section class="textarea-section"><textarea name="postTextArea" placeholder="Your post here" cols="144" rows="3"></textarea><input type="submit" value="-->" class="post-button"> </section>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($postData) && !empty($postData)) {
                        addPost($userid, $postData, 1);
                    } else {
                        echo "Post is empty";
                    }
                }
                ?>
            </form>
            <hr>
            <?php


            if ($postResult != "No posts found") {
                foreach ($postResult as $i => $post) {
                    echo '<section><h4>' . $post['firstname'] . ' ' . $post['lastname'] . '</h4>';
                    echo '<sup>' . $post['created_at'] . '</sup> <br>';
                    echo $post['post_content'] . '<br><section class="inner-html">';
                    foreach (getReactionTypes() as $i => $reactionType) {
                        if (isset($post[$reactionType['name'] . "_reaction"])) {

                            echo "<b class='reaction_label' id='reaction" . $reactionType['id'] . "'>" . $post[$reactionType['name'] . "_reaction"] . '</b> ';
                        }
                        echo '<img class="reactions" src="' . $reactionType['icon'] . '" like-type-id="' . $reactionType['id'] . '" post-id="' . $post["id"] . '"> ';
                    }
                    echo '</section></section><hr>';
                }

            } else {
                echo "No posts found";
            }


            ?>
        </section>
    </section>

</main>
<aside class="setting-box">
    <section class="containers">
        <h3>Account Settings</h3> <br>
        <a class="settings" href="/settings.php">Settings</a> <br>
        <br>
        <a class="settings" href="/logout.php">Logout</a>
    </section>
</aside>
<footer>
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.reactions', function () {
                let obj = $(this)
                $.post("ajax.php",
                    {
                        post_id: $(this).attr('post-id'),
                        like_type_id: $(this).attr('like-type-id'),
                        action: "change_reaction"
                    },
                    function (data) {
                        //console.log(data);
                        if (typeof data.status !== 'undefined' && data.status === 'success') {
                            let content = ""
                            // let reactions = JSON.parse(data)
                            // console.log(reactions)
                            $.each(data.data, function (index, reaction) {
                                content += '<b class="reaction_label" id="reaction' + reaction.id + '"> ' + reaction.count + ' </b><img class="reactions" src="' + reaction.icon + '" like-type-id="' + reaction.id + '" post-id="' + obj.attr('post-id') + '\">';

                            })
                            obj.parent('section').html(content)
                        }
                    });
            });
        });


    </script>
</footer>
</body>
</html>