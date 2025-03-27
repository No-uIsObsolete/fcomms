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
            case 'request_process':
                requestDecision($userid, $_POST['friend_request_id'], $_POST['accept_or_decline']);
                break;
            case 'kick':
                $modifUserId = $_POST['user_id'];
                $groupId = $_POST['group_id'];
                $result = checkRole($userid, $groupId);
                if ($result[0]['is_admin'] < 2) {

                    groupRemoveUser($groupId, $modifUserId);

                }
                else {
                    $data['status'] = "FAILURE!!!1!";
                }
                break;
            case 'member':
                $modifUserId = $_POST['user_id'];
                $groupId = $_POST['group_id'];
                $result = checkRole($userid, $groupId);
                if ($result[0]['is_admin'] === 0) {


                    $AdminChange = 2;
                    groupAdminSwitch($AdminChange, $modifUserId, $groupId);
                }
                else {
                    $data['status'] = "FAILURE!!!1!";
                }

                break;
            case 'admin':
                $modifUserId = $_POST['user_id'];
                $groupId = $_POST['group_id'];
                $result = checkRole($userid, $groupId);
                if ($result[0]['is_admin'] === 0) {


                $AdminChange = 1;
                    groupAdminSwitch($AdminChange, $modifUserId, $groupId);
                }
                else {
                    $data['status'] = "FAILURE!!!1!";
                }


                break;
            case 'getChat':
                $data['data'] = getChatMessages($_POST['from_user_id'], $_POST['to_user_id']);
                setAsRead($_POST['from_user_id'],  $_POST['to_user_id']);
                break;
            case 'sendMessage':
                addChatMessage($_POST['from_user_id'],  $_POST['to_user_id'], $_POST['message']);
                $data['data'] = getChatMessages($_POST['from_user_id'], $_POST['to_user_id']);
                break;
            case 'addComment':
                $post_id = $_POST['post_id'];


                $data['dataPostId'] = $post_id;
                break;
            case 'loadComment':
                $post_id = $_POST['post_id'];
                $comments = getComments($post_id);
                $commentsTree = buildTree($comments);
                $commentsContent = renderComments($commentsTree);

                $data['dataPostId'] = $post_id;
                $data['content'] = $commentsContent;
                break;

            case 'add_post':
                $postData = $_POST['post_content'];
                    addPost($userid, $postData, 1);

                break;

/*___________________________________Commit from 21.03.25_________________________________*/

            case 'comment':
                $comment = $_POST['comment'];
                $post_id = $_POST['post_id'];
                comment($post_id, $comment, $userid);
                break;

            case 'replyTo':
                $comment_id = $_POST['comment_id'];
                $post_id = $_POST['post_id'];
                $comment = $_POST['comment'];
                replyTo($post_id, $comment_id, $comment, $userid);
                break;

/*_______________________________________________________________________________________*/

            case 'private_account':
                $checked = $_POST['checked'];
                if ($checked == 1) {
                    privateAccount($userid, 1);
                }
                else {
                    privateAccount($userid, 0);
                }
                break;

            case 'settings_new_password':
                $errors = [];
                $correct = [];
                $password = trim($_POST['password']);
                $passwordRepeat = trim($_POST['password_repeat']);
                $currentPassword = hash('sha256',trim($_POST['current_password']));
                $hashedPassword = hash('sha256', trim($password));
                $user_password = checkPassword($userid);

                if ($currentPassword != $user_password[0]['password']) {
                    $errors['currentPassword'] = "Current password is incorrect!";
                }
                else {
                    $correct['currentPassword'] = "";
                }

                if ($hashedPassword == $user_password[0]['password']) {
                    $errors['password'] = "The password is the same as the current password";
                }
                else {

                    if (!passwordValidation($password)) {
                        $errors['password'] = "Password should be at least 8 characters long\n And should contain atleast:\n one lowercase character, one uppercase character, one number, one special character,";
                    } else {

                        if (!lowercaseCharactersValidation($password)) {
                            $lowercase = " one lowercase character";
                        } else {
                            $lowercase = "";
                        }

                        if (!uppercaseCharactersValidation($password)) {
                            $uppercase = " one uppercase character";
                        } else {
                            $uppercase = "";
                        }

                        if (!numbersValidation($password)) {
                            $numbers = " one number";
                        } else {
                            $numbers = "";
                        }

                        if (!specialCharactersValidation($password)) {
                            $special_characters = " one special character";
                        } else {
                            $special_characters = "";
                        }

                        if (!empty($lowercase) || !empty($uppercase) || !empty($numbers) || !empty($special_characters)) {
                            if ((!empty($uppercase) || !empty($numbers) || !empty($special_characters) )&& !empty($lowercase)) {
                                $lowercase = $lowercase . ",";
                            }
                            if ((!empty($numbers) || !empty($special_characters)) && !empty($uppercase)) {
                                $uppercase = $uppercase . ",";
                            }
                            if (!empty($special_characters) && !empty($numbers)) {
                                $uppercase = $uppercase . ",";
                            }
                            $errors['password'] = "Password should contain atleast:\n" . $lowercase . $uppercase . $numbers . $special_characters .".";
                        }
                        else {
                            $correct['password'] = "";
                        }

                    }
                }

                if (!empty($passwordRepeat)) {


                    if (!passwordRepeatValidation($password, $passwordRepeat)) {

                        $errors['passwordRepeat'] = "Passwords aren't the same";

                    }
                    else {
                        $correct['passwordRepeat'] = "";
                    }
                }
                else {
                    $errors['passwordRepeat'] = "The textarea is empty";
                }

                if (!empty($errors)) {
                    $data['status'] = "failure";
                    $data['errors'] = $errors;
                    $data['correct'] = $correct;
                }
                else {
                    sqlUpdate('users', 'password = "'.$hashedPassword.'"', "id", $userid);
                    $data['status'] = "success";
                }
                break;

            case 'update-account-details':
                $errors = [];
                $correct = [];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $telephone = $_POST['telephone'];
                $detailResult = checkDetails($userid);

                $result = checkDistinctDetails($userid, $username, $email);
                if (!empty($username)){

                if (isset($result[0]['username']) && !empty($result[0]['username'])) {
                    $errors['username'] = "Username is already taken";
                }
                else {
                    if (!usernameValidation($username)) {
                        $errors['username'] = "Username is invalid";
                    }
                    else
                    {
                        $correct['username'] = $username;
                    }
                }

                }
                else {
                    $errors['username'] = "Username is empty";
                }

                if (!empty($email)){

                    if (isset($result[0]['email']) && !empty($result[0]['email'])) {
                        $errors['email'] = "Email is already taken";
                    }
                    else {
                        if (!emailValidation($email)) {
                            $errors['email'] = "Email is invalid";
                        }
                        else {
                            $correct['email'] = "";
                        }
                    }

                }
                else {
                    $errors['email'] = "Email is empty";
                }

                if (!empty($firstname)){
                    $correct['firstname'] = $firstname;
                }
                else {
                    $errors['firstname'] = "Firstname is empty";
                }
                if (!empty($lastname)){
                    $correct['lastname'] = $lastname;
                }
                else {
                    $errors['lastname'] = "Lastname is empty";
                }

                if (!telephoneValidation($telephone)) {
                    $errors['telephone'] = "Telephone number is invalid";
                }
                else {
                    $correct['telephone'] = "";
                }

                if (!empty($errors)) {
                    $data['status'] = "failure";
                    $data['errors'] = $errors;
                    $data['correct'] = $correct;
                }
                else {
                    if ($username != $detailResult[0]['username']) {
                    sqlUpdate('users', 'username = "'.$username.'"', "id", $userid);
                    }
                    if ($email != $detailResult[0]['email']) {
                        sqlUpdate('users', 'email = "'.$email.'"', "id", $userid);
                    }
                    if ($firstname != $detailResult[0]['firstname']) {
                        sqlUpdate('firstname', 'firstname = "'.$firstname.'"', "id", $userid);
                    }
                    if ($lastname != $detailResult[0]['lastname']) {
                        sqlUpdate('lastname', 'lastname = "'.$lastname.'"', "id", $userid);
                    }
                    if ($telephone != $detailResult[0]['telephone']) {
                        sqlUpdate('users', 'telephone = "'.$telephone.'"', "id", $userid);
                    }
                    $data['status'] = "success";
                }
                break;



                break;

        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

}
?>