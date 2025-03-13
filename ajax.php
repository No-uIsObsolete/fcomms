<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';

if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $action = $_POST['action'];

        $userid = $_SESSION['user']['id'];
        switch ($action) {
            case 'remove_friend':
                $data = $_POST['friend_id'];
                friendDelete($userid, $data);
                break;
            case 'add_friend':
                $data = $_POST['friend_id'];
                friendAdd($userid, $data);
                break;
            case 'remove_request':
                $data = $_POST['friend_id'];
                removeRequest($userid, $data);
                break;
            case 'leave_group':
                $data = $_POST['group_id'];
                leaveGroup($userid, $data);
                break;
            case 'join_group':
                $data = $_POST['group_id'];
                joinGroup($userid, $data);
                break;
            case 'remove_join_request':
                $data = $_POST['group_id'];
                removeJoinRequest($userid, $data);
                break;
            case 'like':
                $postId = $_POST['post_id'];
                $likeTypeId = $_POST['like_type_id'];
                likes($postId, $likeTypeId, $userid);
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



        $data = ['status' => 'success'];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);


    }

}




?>