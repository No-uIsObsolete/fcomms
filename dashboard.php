<?php
require 'src/functions.php';
//$errors = [];
if (isset($_SESSION['user'])) {
    //echo "<pre>";
    //var_dump($_SESSION['user']); die;
    $userid = $_SESSION['user']['id'];
    $userFirstname = $_SESSION['user']['firstname'];
    $userLastname = $_SESSION['user']['lastname'];
    $friendResult = getFriends($userid);
    $groupResult = getGroups($userid);
    $postResult = getPosts($userid);


} else {
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$postData = $_POST['postTextArea'];
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
    <link
            href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css"
            rel="stylesheet"
    />




</head>
<body>



<header>
    <h1 class="logo">FComms</h1> <section class="user-logged-in"><?php echo $userFirstname." ".$userLastname ?></section> <a class="logout-button" href="/logout.php">Logout</a>
</header>
<aside class="social-box">
    <section class="containers">
        <h3>Friend list</h3>
        <form method="get" action="users.php">
            <section class="search-bar"><label>Add more:</label> <input class="search-box" type="search" placeholder="Search" name="friendSearch"><input class="search-buttons" type="submit" value="Search"></section>
        </form>
        <hr>
        <section>
            <ul class='friend-list-ul'>
                <?php
                //var_dump($friendResult);
                if (isset($friendResult) ) {
                    $row = 0;
                    foreach ($friendResult as $friend) {
                        echo "
            
            
                <li class='friend-list-li'>
                <a class='lists' href='chat.php?friend=".$friend['friend_user_id']."'>  <img src=" . $friend['profile_picture'] . " alt='profile_picture' class='pfp'> <b class='friend-list-a'>" . $friend['firstname'] . " " . $friend['lastname'] . "</b> </a>
                </li>
            
            <hr>
            ";

                    }
                }
                ?>
            </ul>
        </section>
    </section>
    <section class="containers" id="group-container">
        <h3>Groups</h3>
        <form form method="get" action="groups.php">
            <section class="search-bar"><label>Join more:</label> <input class="search-box" type="search" placeholder="Search" name="groupSearch"><input class="search-buttons" type="submit" value="Search"></section>
        </form>
        <hr>
        <section>
            <ul class='group-ul'>
                <?php
                //var_dump($groupResult);
                if (isset($groupResult)) {
                    $row = 0;
                    foreach ($groupResult as $group) {
                        echo "
            
            
                <li class='group-li'>
                <a class='lists' href='group.php?group=".$group['id']."'>" . $group['group_name'] . " </a>
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
        <h3>Feed</h3> <br>
        <section>
            <hr>
<!--            <form method="post" action="/dashboard.php">-->
                <section class="textarea-section"><!-- Create the toolbar container -->
                    <div id="toolbar">
<!--                        <button class="ql-bold">Bold</button>-->
<!--                        <button class="ql-italic">Italic</button>-->
                    </div>

                    <!-- Create the editor container -->
                    <div id="editor" name="postTextArea" >
                        <p>Hello World!</p>
                        <p>Some initial <strong>bold</strong> text</p>
                        <p><br /></p>
                    </div>
<!--                    <textarea name="postTextArea" placeholder="Your post here" cols="144" rows="3"></textarea>-->
                    <button type="submit" value="submitPost" class="feed-post-button"> --> </button></section>
                <?php
//                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//                    if (isset($postData) && !empty($postData)) {
//                        addPost($userid, $postData, 1);
//                        header('Location: dashboard.php');
//                    } else {
//                        echo "Post is empty";
//                    }
//                }
                ?>
<!--            </form>-->
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
                    echo '<br>';
                    $comments = getComments($post["id"]);

                    if ( !empty($comments) ) {

                    echo  '<section comments-for="'.$post["id"].'" class="commentLoader" action="loadComment"> <sub> <strong> &#8226; </strong> Load Comments </sub> </section>
                    <section style="display: none;" post-id="'.$post['id'].'" > <div comment="'.$post["id"].'"></div>
                     <input type="text" add-comment-to="'.$post['id'].'"><button>Submit</button></section>';
                    }
                    else {
                        echo '<section comments-for="'.$post["id"].'" class="commentAdd" action="addComment"> <sub> <strong> + </strong> Add Comment</sub> </section>
                        <section style="display: none;" post-id="'.$post['id'].'" ><input type="text" add-comment-to="'.$post['id'].'"><button>Submit</button></section>';
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
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
        const quill = new Quill("#editor", {
            theme: "snow",
        });

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

            $(document).on('click', 'button[value="submitPost"]', function () {

                $.post("ajax.php",
                    {
                        post_content: quill.getSemanticHTML(0),
                        action: "add_post"
                    },
                    function (data) {
                        if (typeof data.status !== 'undefined' && data.status === 'success') {
                            location.reload()
                        }
                    });
            });




            $(document).on('click', 'section[action="loadComment"]', function () {
                let post_id = $(this).attr('comments-for')
                let action = $(this).attr('action')
                $('section[post-id="'+post_id+'"]').toggle()
                $.post("ajax.php",
                    {
                        post_id: post_id,
                        action: action
                    },
                    function (data) {
                            if (typeof data.status !== 'undefined' && data.status === 'success') {
                                location.reload()

                                $('div[comment="' + post_id + '"]').html(data.content)
                            }
                    });
            });

            $(document).on('click', 'section[action="addComment"]', function () {
                let post_id = $(this).attr('comments-for')
                let action = $(this).attr('action')
                $('section[post-id="'+post_id+'"]').toggle()
                $.post("ajax.php",
                    {
                        action: action
                    },
                    function (data) {
                            if (typeof data.status !== 'undefined' && data.status === 'success') {
                                location.reload()
                            }


                    });
            });






        });


    </script>
</footer>
</body>
</html>