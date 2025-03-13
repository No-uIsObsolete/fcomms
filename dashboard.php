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

}
else {
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
        <label>
            Add more: <input type="text" placeholder="Search" name="friendSearch">
        </label>
            <input type="submit" value="Search">
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
                <a>  <img src=".$friendResult[$row]['profile_picture']." alt='profile_picture' class='pfp'>" . $friendResult[$row]['firstname'] . " " . $friendResult[$row]['lastname'] . " </a>
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
            <label>
                Join more: <input type="text" placeholder="Search" name="groupSearch">
            </label>
            <input type="submit" value="Search">
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
        <textarea name="postTextArea" placeholder="Your post here" cols="168" rows="3" ></textarea><input type="submit" value="Post">
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
        foreach ($postResult as $i=> $post) {
            echo '<section><h4>'.$post['firstname'].' '.$post['lastname'].'</h4>';
            echo '<sup>'.$post['created_at'].'</sup> <br>';
            echo $post['post_content'].'<br>';
            foreach (getReactionTypes() as $i => $reactionType) {
                if (isset($post[$reactionType['name']."_reaction"])) {
                    echo $post[$reactionType['name']."_reaction"].' ';
                }
            echo '<img class="likes" src="'.$reactionType['icon'].'" like-type-id="'.$reactionType['id'].'" post-id="'.$post["id"].'"> ';
            }
//            <img class="likes"src="/assets/css/dislike.png" like-type-id="0" post-id="'.$post["id"].'">
//            <img class="likes"src="/assets/css/laughingface.png" like-type-id="2" post-id="'.$post["id"].'">
//            <img class="likes"src="/assets/css/neutralface.png" like-type-id="3" post-id="'.$post["id"].'">
//            <img class="likes"src="/assets/css/sadface.png" like-type-id="4" post-id="'.$post["id"].'">
            echo '</section><hr>';
        }

    }
    else {
        echo "No posts found";
    }


    ?>
    </section>
</section>

</main>
<aside class="setting-box">
<section class="containers">
    <h3>Account Settings</h3>
    <a href="/settings.php">Settings</a> <br>
    <br>
    <a href="/logout.php">Logout</a>
</section>
</aside>
<footer>
<script src="assets/js/jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    $(document).on('click', '.likes', function(){
        let obj = $(this)
        //console.log($(this).attr('for-post'))
        //console.log($(this).attr('like-value'))
        $.post("ajax.php",
            {
                post_id: $(this).attr('post-id'),
                like_type_id: $(this).attr('like-type-id'),
                action: "like"
            },
            function(data){
                console.log(data);
                if (typeof data.status !== 'undefined' && data.status === 'success')
                {


                }
            });
    });
    });


</script>
</footer>
</body>
</html>