<?php
require 'src/functions.php';


$groupid = $_GET['group'];

if (empty($groupid)) {header('Location: index.php');}
else {
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['id'];

    $grouplist = getUserGroup($userid, $groupid);
    if ($grouplist == "Return Now") {
        header('Location: dashboard.php');

    }
    else {

    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST['postTextArea'];
    }
}
else {
    header('Location: index.php');
}

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

    </section> </aside>
<main>
<section class="containers">
    <h3>Feed</h3> <br>
    <section>


        <hr>
        <form method="post" action="/group.php?group="<?php echo $groupid?>">
        <section class="textarea-section"> <textarea name="postTextArea" placeholder="Your post here" cols="104" rows="3"></textarea><input type="submit" value="-->" class="post-button"> </section>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($postData) && !empty($postData)) {
                    addGroupPost($userid, $postData, 2, $groupid);
                    header('Location: group.php?group='.$groupid);
                } else {
                    echo "Post is empty";
                }
            }
            ?>
        </form>
        <hr>

    </section>
</section>
</main>
<aside>
    <section class="containers">

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
