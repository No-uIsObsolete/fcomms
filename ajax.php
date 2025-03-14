<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';

if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = ['status' => 'success'];
        $action = $_POST['action'];

        $userid = $_SESSION['user']['id'];
        switch ($action) {
            case 'remove_friend':
                friendDelete($userid, $_POST['friend_id']);
                break;
            case 'add_friend':
                friendAdd($userid, $_POST['friend_id']);
                break;
            case 'remove_request':
                removeRequest($userid, $_POST['friend_id']);
                break;
            case 'leave_group':
                leaveGroup($userid, $_POST['group_id']);
                break;
            case 'join_group':
                joinGroup($userid, $_POST['group_id']);
                break;
            case 'remove_join_request':
                removeJoinRequest($userid, $_POST['group_id']);
                break;
            case 'change_reaction':
                $postId = $_POST['post_id'];
                $likeTypeId = $_POST['like_type_id'];
                addReaction($postId, $likeTypeId, $userid);
                $data['data'] = getPostReactions($postId);

                break;
//            case 'delete_user':
//                if ($data == 0) {
//                    deleteUser($userid);
//                    session_unset();
//                    session_destroy();
//                }
//                break;
//            case 'private_account':
//                    privateAccount($userid, $data);
//                break;

        }




        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);


    }

}




?>