<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';

if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST['friend_id'];
        $action = $_POST['action'];

        $userid = $_SESSION['user']['id'];
        switch ($action) {
            case 'remove_friend':
                friendDelete($userid, $data);
                break;
            case 'add_friend':
                friendAdd($userid, $data);
                break;
            case 'remove_request':
                removeRequest($userid, $data);
                break;
            case 'leave_group':
                leaveGroup($userid, $data);
                break;
            case 'join_group':
                joinGroup($userid, $data);
                break;
            case 'remove_join_request':
                removeJoinRequest($userid, $data);
                break;
            case 'delete_user':
                if ($data == 0) {
                    deleteUser($userid);
                    session_unset();
                    session_destroy();
                }
                break;
            case 'private_account':
                    privateAccount($userid, $data);
                break;

        }



        $data = ['status' => 'success'];
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);


    }

}




?>