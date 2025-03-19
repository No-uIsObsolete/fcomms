<?php
require 'src/functions.php';


$groupid = $_GET['group'];

if (empty($groupid)) {
    header('Location: index.php');
} else {
    if (isset($_SESSION['user'])) {
        $userid = $_SESSION['user']['id'];
        $userFirstname = $_SESSION['user']['firstname'];
        $userLastname = $_SESSION['user']['lastname'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $submitButton = $_POST['submit'];
            switch ($submitButton) {
                case "sendPost":
                    $postData = $_POST['postTextArea'];
                    break;


                case "editGroup":
                    $name = $_POST['groupName'];
                    if (isset($_POST['groupPrivate'])) {
                        $private = $_POST['groupPrivate'];
                    } else {
                        $private = 0;
                    }
                    editGroup($groupid, $private, $name);
                    header('Location: /group.php?group=' . $groupid);
                    break;
            }


        }


        $grouplist = getUserGroup($userid, $groupid);

        if ($grouplist == "Return Now") {
            header('Location: dashboard.php');

        } else {
            $postResult = getGroupPosts($groupid);
            $currentRole = checkRole($userid, $groupid);

        }

    } else {
        header('Location: index.php');
    }
}



if ($grouplist[0]['is_private'] == 1) {
    $checkbox = "checked";
} else {
    $checkbox = "";
}

if ($currentRole[0]['is_admin'] == 1 || $currentRole[0]['is_admin'] == 0) {
    $panel = "admin-view";
} else {
    $panel = "guest-view";
}



?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $grouplist[0]['group_name'] ?></title>
    <link rel="stylesheet" href="assets/css/search-style.css">
</head>
<body>
<header><h1 class="logo">FComms</h1> <section class="user-logged-in"><?php echo $userFirstname." ".$userLastname ?></section> <a class="logout-button" href="/logout.php">Logout</a></header>
<aside class="group-box">
    <section class="containers">
        <h2><?php echo $grouplist[0]['group_name'] ?></h2>
    </section>
</aside>
<main>
    <section class="containers">
        <h3>Feed</h3> <br>
        <section>


            <hr>
            <form method="post" action="/group.php?group=<?php echo $groupid ?>">
                <section class="textarea-section"><textarea name="postTextArea" placeholder="Your post here" cols="104"
                                                            rows="3"></textarea><button type="submit" value="sendPost" name="submit" class="post-button">--></button>
                </section>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($postData) && !empty($postData)) {
                        addGroupPost($userid, $postData, 2, $groupid);
                        header('Location: group.php?group=' . $groupid);
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
<aside class="<?php echo $panel ?>">
    <section class="containers">

        <h3>Members:</h3>
        <hr>

        <?php
        foreach (checkUsers($groupid) as $i => $groupMembers) {
            if ($groupMembers['is_admin'] == 1) {
                $groupRole = "Admin";
                $adminbutton = "<button>Change to Member</button>";
            }
            if ($groupMembers['is_admin'] == 0) {
                $groupRole = "Owner";
            }
            if ($groupMembers['is_admin'] == 2) {
                $groupRole = "Member";
                $adminbutton = "<button>Change to Admin</button>";
            }

            echo "<br> <a>  <img src=" . $groupMembers['profile_picture'] .
                " alt='profile_picture' class='pfp'> <b class='role-list-a'>" . $groupMembers['firstname'] .
                " " . $groupMembers['lastname'] . " <i>" . $groupRole . "</i></b>";

            if (checkRole($userid, $groupid)[0]['is_admin'] == 0 && $groupMembers['is_admin'] > 0 && $groupMembers['id'] != $userid) {
            if ($groupMembers['is_admin'] == 1) {
                echo " <br>  <br> <button user-id='" . $groupMembers['id'] . "' ajax-action='member' group-id='".$groupid."' >Change to Member</button>";
            } elseif ($groupMembers['is_admin'] == 2) {
                echo " <br>  <br> <button user-id='" . $groupMembers['id'] . "' ajax-action='admin' group-id='".$groupid."'>Change to Admin</button>";

                }
            }
        if (checkRole($userid, $groupid)[0]['is_admin'] < 1) {
            if ($groupMembers['is_admin'] != 0) {
                echo "  <br>  <br> <button user-id='" . $groupMembers['id'] . "' ajax-action='kick' group-id='" . $groupid . "'>Remove user</button>";
            }
        }
        elseif (checkRole($userid, $groupid)[0]['is_admin'] < 2) {
            if ($groupMembers['is_admin'] > 1) {
                echo "  <br>  <br> <button user-id='" . $groupMembers['id'] . "' ajax-action='kick' group-id='" . $groupid . "'>Remove user</button>";
            }
        }

            echo "</a> <br> <br> <hr>";
        }


        ?>
    </section>
    <?php
    if ($panel == "admin-view") {
        echo "<section class='containers'>";
        echo "
            <form action='/group.php?group=" . $groupid . "' method='post' name='Admin_Panel'>
            
            
            
            Group Name: <input type='text' value='" . $grouplist[0]['group_name'] . "' name='groupName'> <br>
            Set as Private: <input type='checkbox' name='groupPrivate' value='1' " . $checkbox . "> <br>
            <button type='submit' name='submit' value='editGroup'>Save</button>
            </form>
            
            
            ";
        if ($currentRole[0]['is_admin'] == 0) {
            echo "
                
                ";
        }
        echo "</section>";
    } else {
        echo "";
    }


    ?>


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
            $(document).on('click', 'button[ajax-action][user-id][group-id]', function () {
                let obj = $(this)
                let userId = $(this).attr('user-id')
                let ajaxAction = $(this).attr('ajax-action')
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                let groupId = urlParams.get('group')
                // let groupId = $(this).attr('group-id')
                //console.log(userId, ajaxAction, groupId)
                $.post("ajax.php",
                    {
                        user_id: userId,
                        group_id: groupId,
                        action: ajaxAction
                    },
                    function (data) {
                        console.log(data);
                        location.reload();
                    });
            });
        });


    </script>
</footer>
</body>
</html>
