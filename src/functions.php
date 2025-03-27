<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

function connect()
{
    return mysqli_connect("195.201.38.255", "afura_fcomms", ")Pc8p[fEpO+Qy:6*", "afura_fcomms");
}

function addUser($user, $password, $email, $firstname, $lastname, $telephone)
{

    $currentTime = date("Y-m-d H:i:s");
    $hashedPassword = hash('sha256', $password);


    $table = 'users';
    sqlInsert($table, ['username' => $user, 'password' => $hashedPassword, 'email' => $email, 'firstname' => $firstname, 'lastname' => $lastname,
        'telephone' => $telephone, 'created_at' => $currentTime, 'updated_at' => $currentTime, 'status' => 1]);
}

function sqlResult($query)
{
    $con = connect();
    $sql = mysqli_query($con, $query);
    $result = array();
    while ($row = $sql->fetch_assoc()) {
        $result[] = $row;
    }
    return $result;
}

function sqlInsert($table, $params)
{
    $con = connect();
    $keys = array_keys($params);
    $values = array_values($params);


    $query = "INSERT INTO $table ( " . implode(", ", $keys) . " ) VALUES ( '" . implode("', '", $values) . "');";
    $sql = mysqli_query($con, $query);

}

function sqlUpdate($table, $params, $target, $targetData)
{
    $con = connect();


    $query = "UPDATE $table
                SET $params
                WHERE $target = '$targetData'";
    $sql = mysqli_query($con, $query);

}
function sqlUpdateMultiple($table, $params, $target)
{
    $con = connect();


    $query = "UPDATE $table
                SET $params
                WHERE $target";
//    var_dump($query); die;
    $sql = mysqli_query($con, $query);

}

function sqlDelete($table, $params)
{
    $con = connect();
    // = array_keys($params);
    //$values = array_values($params);
    $tmp = '';
    foreach ($params as $key => $value) {
        $tmp .= $key . ' = "' . $value . '" AND ';
        //var_dump($key);
        //var_dump($value);

    }
    $tmp = rtrim($tmp, ' AND ');
    $query = "DELETE FROM $table WHERE $tmp ";
    $sql = mysqli_query($con, $query);
}


function checkUsername($username)
{

    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = sqlResult($query);
    if (isset ($result[0])) {
        if ($result[0]['username'] == $username) {
            return false;
        }

    } else {
        return true;
    }

}

function checkEmail($email)
{
    $query = "SELECT email FROM users WHERE email = '$email'";
    $result = sqlResult($query);
    if (isset ($result[0])) {
        if ($result[0]['email'] == $email) {
            return false;
        }

    } else {
        return true;
    }
}

function usernameValidation($username)
{
    if (str_contains($username, ' ')) {
        return false;
    } else {
        return true;
    }
}

function emailValidation($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }

}

function telephoneValidation($telephone)
{
    if ($telephone == "") {
        return true;
    } else {
        $find = '+';
        $pos = strpos($telephone, $find);
        if ($pos !== false) {
            if (strlen($telephone) > 7 && strlen($telephone) <= 15) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*$pattern = "/^\+?\d{1,4}?[-.\s]?(\(?\d{1,3}?\)?[-.\s]?)?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/";
    if (preg_match($pattern, $telephone)) {
        return true;
    } else {
        return false;
    }*/

}

function uppercaseCharactersValidation($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    if (!$uppercase) {
        return false;
    } else {
        return true;
    }
}

function lowercaseCharactersValidation($password)
{
    $lowercase = preg_match('@[a-z]@', $password);
    if (!$lowercase) {
        return false;
    } else {
        return true;
    }
}

function numbersValidation($password)
{
    $number = preg_match('@[0-9]@', $password);
    if (!$number) {
        return false;
    } else {
        return true;
    }
}

function specialCharactersValidation($password)
{
    $specialChars = '!@#$%^&*()-_=+[{]};:\'",<.>/?\\|';
    if (strpbrk($password, $specialChars) === false) {
        return false;
    } else {
        return true;
    }
}


function passwordValidation($password)
{
    if (strlen($password) > 7) {

        return true;

    } else {
        return false;
    }
}


function passwordRepeatValidation($password, $passwordRepeat)
{
    if ($passwordRepeat != $password) {
        return false;
    } else {
        return true;
    }
}

function mainValidation($username, $password, $email, $firstname, $lastname)
{
    if (strlen($username) == 0 || strlen($password) == 0 || strlen($firstname) == 0 || strlen($lastname) == 0 || strlen($email) == 0) {
        return false;
    } else {
        return true;
    }
}

function getUser($user, $password)
{
    $hashedPassword = hash('sha256', $password);
    $query = "SELECT * FROM users where (username = '$user' or email = '$user') AND password = '$hashedPassword' LIMIT 1";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result[0];
    }

}

function checkLogin($user, $password)
{

    $hashedPassword = hash('sha256', $password);
    $query = "SELECT id, username, email, `password`, status FROM users where (username = '$user' or email = '$user') AND password = '$hashedPassword'";
    $result = sqlResult($query);

    if (isset($result[0])) {
        if ($result[0]['status'] == 1) {
            return "Success";
        } else {
            return "The user is has not been activated or has been banned.";
        }
    } else {
        return "The parameters are wrong or this user does not exist.";
    }
}

function emailExists($email)
{

    $query = "SELECT email FROM users WHERE email = '$email'";

    if (!empty(sqlResult($query))) {
        return true;
    } else {
        return false;
    }

}

function createToken($email)
{

    $query = "SELECT id FROM users WHERE email = '$email'";

    $result = sqlResult($query);

    if (isset($result[0]['id'])) {
        $userid = $result[0]['id'];
    }


    $currentTime = date("Y-m-d H:i:s");
    $status = 0;
    $token = bin2hex(random_bytes(20));
    $table = "tokens";
    $query2 = "SELECT users.email FROM users, tokens WHERE email = '$email' AND tokens.userid = users.id AND tokens.created_at > DATE_SUB( '$currentTime', INTERVAL 15 MINUTE )";
    $result2 = sqlResult($query2);

    if (isset($result2[0])) {
        return "Token already exists, please check your email";
    } else {
        sqlInsert($table, ['token' => $token, 'userid' => $userid, 'created_at' => $currentTime, 'updated_at' => $currentTime, 'status' => $status]);
        SendPasswordResetEmail($token);
        return "Your token request has been sent to your email";
    }
}

function checkToken($password, $token)
{

    $hashedPassword = hash('sha256', $password);
    $currentTime = date("Y-m-d H:i:s");
    $status = 1;

    $table = "users";
    $table2 = "tokens";

    $query = "SELECT users.id, tokens.status FROM users, tokens WHERE token = '$token' AND tokens.userid = users.id AND tokens.status = 0 AND tokens.created_at > DATE_SUB( '$currentTime', INTERVAL 15 MINUTE )";
    $result = sqlResult($query);

    $target = "id";

    $target2 = "token";

    $params = "password = '$hashedPassword'";
    $params2 = "tokens.status = '$status', tokens.updated_at = '$currentTime'";


    if (isset($result[0])) {
        $targetData = $result[0]['id'];
        sqlUpdate($table, $params, $target, $targetData);
        sqlUpdate($table2, $params2, $target2, $token);
        header('Location: login.php');
        return "Successfully changed your password <br>";
    } else {


        return "Password token already expired or used <br>";
    }
}

function SendPasswordResetEmail($token)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
//        $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'mail30.mydevil.net';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'a.fura@kgtech.pl';                     //SMTP username
        $mail->Password = '3nT+uJ[4F]50uGm*pbYLWoq9hCN9A3';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('a.fura@kgtech.pl', 'PasswordResetAutobot');
        $mail->addAddress('a.fura@kgtech.pl', 'Andrzej Fura');     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset';
        $mail->Body = '<h1> &nbsp; Forgot Your Password? </h1> &nbsp; This is your password reset authorization token: 
        <a href="http://fcomms.website/set-password.php?token=' . $token . '">
        Reset Password</a> <br> <br> <br> &nbsp; If This is not you please ignore this email. <br> <br>';
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        //echo 'Message has been sent';
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function getFriends($user)
{
    $query = "SELECT main_user_id, friend_user_id, firstname, lastname, profile_picture FROM friend_list, users WHERE main_user_id = '$user' AND friend_user_id = users.id AND status = 1 ORDER BY firstname";
    return sqlResult($query);
}

function getGroups($user)
{
    $query = "SELECT groups.id ,group_user_id, group_id , group_name FROM groups, groups_and_users, users WHERE group_user_id = '$user' AND group_user_id = users.id AND group_id = groups.id ORDER BY group_name";
    return sqlResult($query);
}


function searchUsers($user, $mainUserId)
{
    $userLowCase = strtolower($user);

    $query = "SELECT id, firstname, lastname, profile_picture, friend_request.request_status, 
              IF(friend_list.main_user_id>0, 1, 0) AS is_friend,
              IF(friend_request.from_user_id = $mainUserId, 1, 0) AS request_to
              FROM users
              LEFT JOIN friend_list
              ON users.id = friend_list.friend_user_id AND friend_list.main_user_id = $mainUserId
              LEFT JOIN friend_request
              ON users.id = friend_request.to_user_id AND friend_request.from_user_id = $mainUserId 
              WHERE  (LOWER(firstname) LIKE '%$userLowCase%' or LOWER(lastname) LIKE '%$userLowCase%') AND users.status = 1 
              AND users.id <> '$mainUserId' AND users.private_account = 0 GROUP BY users.id;";
    //var_dump($query); die;
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    } else {

        return 'No users found';
    }
}

function searchGroups($group, $userId)
{
    $groupLowCase = strtolower($group);

    $query = "SELECT groups.id, group_name,
			  IF(groups_and_users.group_user_id>0, 1, 0) AS member, 
			  IF(group_join_request.user_id>0, 1, 0) as join_request
              FROM groups
              LEFT JOIN groups_and_users
              ON groups_and_users.group_id = groups.id  AND groups_and_users.group_user_id = $userId
              LEFT JOIN group_join_request
              ON group_join_request.group_id = groups.id AND group_join_request.user_id = $userId
              WHERE groups.is_private = 0 AND LOWER(group_name) LIKE '%$groupLowCase%' GROUP BY groups.id;";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    } else {
        return "No groups found";
    }
}

function friendDelete($userId, $friendId)
{
    sqlDelete('friend_list', ['main_user_id' => $userId, 'friend_user_id' => $friendId]);
    sqlDelete('friend_list', ['main_user_id' => $friendId, 'friend_user_id' => $userId]);


}

function friendAdd($userId, $friendId)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('friend_request', ['from_user_id' => $userId, 'to_user_id' => $friendId, 'request_date' => $currentTime, 'request_status' => 1]);
}

function removeRequest($userId, $friendId)
{
    sqlDelete('friend_request', ['from_user_id' => $userId, 'to_user_id' => $friendId]);
}

function leaveGroup($userId, $groupId)
{
    sqlDelete('groups_and_users', ['group_user_id' => $userId, 'group_id' => $groupId]);
}

function joinGroup($userId, $groupId)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('group_join_request', ['user_id' => $userId, 'group_id' => $groupId, 'request_date' => $currentTime, 'request_status' => 1, 'request_type' => 1]);
}

function removeJoinRequest($userId, $groupId)
{
    sqlDelete('group_join_request', ['user_id' => $userId, 'group_id' => $groupId, 'request_type' => 1]);
}

function deleteUser($userId)
{
    $closedToken = bin2hex(random_bytes(20));
    $query = "SELECT users.id, users.username, users.email, users.status FROM users where users.id = '$userId';";
    $result = sqlResult($query);
    $email = $result[0]['email'] . "-closed" . $closedToken;
    $username = $result[0]['username'] . "-closed" . $closedToken;
    $target = "id";
    $params = "status = '0', email = '$email', username = '$username'";
    sqlUpdate('users', $params, $target, $userId);
}

function privateAccount($userId, $data)
{
    $target = "id";
    $params = "private_account = '$data'";
    sqlUpdate('users', $params, $target, $userId);
}

function getReactionTypes()
{
    return sqlResult("SELECT * FROM reaction_types");
}

function getPosts($mainUserId)
{


    $reactionCols = array_map(function ($item) {
        return "SUM(CASE WHEN rt.id = {$item['id']} THEN 1 ELSE 0 END) AS {$item['name']}_reaction";
    }, getReactionTypes());

    $query = "SELECT COUNT(comments.id) AS comment_count, posts.id, users.id AS post_user_id, post_content, post_type, posts.created_at, firstname, lastname, profile_picture , " . implode(', ', $reactionCols) . "
              FROM posts
              LEFT JOIN users 
              ON posts.user_id = users.id
              LEFT JOIN reactions
              ON posts.id = reactions.post_id AND reactions.enabled = 1
              LEFT JOIN reaction_types rt
              ON reactions.type_id = rt.id
              LEFT JOIN comments
              ON posts.id = comments.post_id
              WHERE (posts.user_id IN(SELECT friend_user_id FROM friend_list WHERE main_user_id = $mainUserId) OR posts.user_id = $mainUserId) AND users.status = 1 AND post_type = 1 GROUP BY posts.id ORDER BY posts.created_at DESC
              ";
    echo "<pre>";
    var_dump($query); die;
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    } else {
        return "No posts found";
    }
}

function addPost($userId, $data, $type)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('posts', ['user_id' => $userId, 'post_content' => $data, 'post_type' => $type, 'created_at' => $currentTime, 'updated_at' => $currentTime]);
}

//function randomUser()
//{
//    $query = "SELECT id FROM users WHERE id>12 ORDER BY RAND() LIMIT 1
//              ";
//    $result = sqlResult($query);
//    $data = bin2hex(random_bytes(30));
//    posts($result[0]['id'],$data,1);
//}
//function posts($userId, $data, $type)
//{
//    $currentTime = date("Y-m-d H:i:s");
//    sqlInsert('posts', ['user_id' => $userId, 'post_content' => $data, 'post_type' => $type, 'created_at' => $currentTime, 'updated_at' => $currentTime]);
//}

function addReaction($postId, $likeTypeId, $userId)
{
    $con = connect();
    $query = "INSERT INTO reactions (post_id, from_user_id, type_id, enabled) VALUES ($postId, $userId, $likeTypeId, 1)
                ON DUPLICATE KEY UPDATE ENABLED = IF(type_id <> $likeTypeId, 1,IF(ENABLED=1, 0, 1)), type_id= " . $likeTypeId . ";";
    mysqli_query($con, $query);
}

function getPostReactions($postId)
{
    $query = <<<EOF
SELECT r.type_id, rt.`name`, COUNT(rt.id) AS count FROM reactions r
LEFT JOIN reaction_types rt
ON r.type_id = rt.id
WHERE r.enabled = 1 AND r.post_id = {$postId} GROUP BY rt.`name`
EOF;
    $reactionCounts = array();
    foreach (sqlResult($query) as $reactionCount) {
        $reactionCounts[$reactionCount['type_id']] = $reactionCount;
    }
    $reactions = [];
    foreach (getReactionTypes() as $i => $reactionType) {
        $reactionType['count'] = 0;
        if (isset($reactionCounts[$reactionType['id']])) {
            $reactionType['count'] = $reactionCounts[$reactionType['id']]['count'];
        }
        $reactions[$reactionType['id']] = $reactionType;
    }
    return $reactions;
}

function friendRequestGet($MainUserId)
{
    $query = "SELECT friend_request.from_user_id, request_status, firstname, lastname  FROM  friend_request
              LEFT JOIN users
              ON friend_request.from_user_id = users.id  
              WHERE users.status = 1 AND friend_request.to_user_id = ".$MainUserId." AND friend_request.request_status = 1 ";
    $result = sqlResult($query);
    if (isset($result)) {
        return $result;
    } else {
        return "No requests found";
    }

}

function requestDecision($mainUserId, $requestUserId, $input)
{
    if ($input == 1) {
        $currentTime = date("Y-m-d H:i:s");
        sqlInsert('friend_list', ['main_user_id' => $mainUserId, 'friend_user_id' => $requestUserId, 'friends_from' => $currentTime]);
        sqlInsert('friend_list', ['main_user_id' => $requestUserId, 'friend_user_id' => $mainUserId, 'friends_from' => $currentTime]);
        sqlDelete('friend_request',['from_user_id' => $requestUserId, 'to_user_id' => $mainUserId]);
    }
    else {
        sqlDelete('friend_request',['from_user_id' => $requestUserId, 'to_user_id' => $mainUserId]);
    }


}
function getUserGroups($userId)
{

    $query = "SELECT groups.id, groups.group_name, group_join_request.from_user_id, group_join_request.request_type
              FROM groups
              LEFT JOIN groups_and_users
              ON groups_and_users.group_id = groups.id  AND groups_and_users.group_user_id = $userId
              LEFT JOIN group_join_request
              ON group_join_request.group_id = groups.id
              AND groups_and_users.group_user_id = '$userId'";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    } else {
        return "No groups found";
    }
}
function getUserGroup($userId, $groupId)
{

    $query = "SELECT groups.id, groups.group_name, groups.is_private
              FROM groups
              LEFT JOIN groups_and_users
              ON groups_and_users.group_id = groups.id  AND groups_and_users.group_user_id = $userId
              WHERE groups_and_users.group_user_id = '$userId' AND groups.id = '$groupId'";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    } else {
        return "Return Now";
    }
}
function addGroupPost($userId, $data, $type, $groupId)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('posts', ['user_id' => $userId, 'post_content' => $data, 'post_type' => $type, 'created_at' => $currentTime, 'updated_at' => $currentTime, 'group_id' => $groupId]);
}
function getGroupPosts($groupId)
{


    $reactionCols = array_map(function ($item) {
        return "SUM(CASE WHEN rt.id = {$item['id']} THEN 1 ELSE 0 END) AS {$item['name']}_reaction";
    }, getReactionTypes());

    $query = "SELECT posts.id, users.id AS post_user_id, post_content, post_type, posts.created_at, firstname, lastname, profile_picture , " . implode(', ', $reactionCols) . "
              FROM posts
              LEFT JOIN users 
              ON posts.user_id = users.id
              LEFT JOIN reactions
              ON posts.id = reactions.post_id AND reactions.enabled = 1
              LEFT JOIN reaction_types rt
              ON reactions.type_id = rt.id
              LEFT JOIN groups_and_users gu
              ON gu.group_user_id = users.id
              WHERE gu.group_id = $groupId AND posts.group_id = $groupId AND users.status = 1 AND post_type = 2 GROUP BY posts.id ORDER BY posts.created_at DESC
              ";
//    echo "<pre>";
//    var_dump($query); die;
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    } else {
        return "No posts found";
    }
}

function checkUsers($groupId)
{
    $query = "SELECT users.id, firstname, lastname, profile_picture, is_admin
              FROM groups_and_users
              LEFT JOIN users
              ON groups_and_users.group_user_id = users.id
              LEFT JOIN groups 
              ON groups_and_users.group_id = groups.id
              WHERE group_id = ".$groupId."
              ORDER BY is_admin";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    }
}

function checkRole($userid, $groupId)
{
    $query = "SELECT is_admin
              FROM groups_and_users
              LEFT JOIN users
              ON groups_and_users.group_user_id = users.id
              LEFT JOIN groups 
              ON groups_and_users.group_id = groups.id
              WHERE users.id = ".$userid." AND groups.id = ".$groupId." ";

    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result;
    }
}

function editGroup($groupId, $isPrivate, $groupName)
{
    $currentTime = date("Y-m-d H:i:s");
    $target = "groups.id";
    $params = "updated_at = '$currentTime', group_name = '$groupName', is_private = '$isPrivate'";
    sqlUpdate('groups', $params, $target, $groupId);
}
function groupAdminSwitch($admin, $userId, $groupId)
{
    $target = "groups_and_users.group_id = '$groupId', groups_and_users.group_user_id = '$userId'";
    $params = "is_admin = '$admin'";
    sqlUpdateMultiple('groups_and_users', $params, $target);
}
function groupRemoveUser($groupId, $userId)
{
sqlDelete('groups_and_users', ['group_user_id' => $userId, 'group_id' => $groupId]);
}
function checkFriendChat($userId, $friendId)
{
    $query = "SELECT id, firstname, lastname, profile_picture
              FROM users
              LEFT JOIN friend_list
              ON friend_list.friend_user_id = users.id 
              WHERE users.id = '$friendId' AND friend_list.main_user_id = '$userId'";

    return sqlResult($query);

}

function addChatMessage($from_user_id, $to_user_id, $message)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('messages', ['from_user_id' => $from_user_id, 'to_user_id' => $to_user_id, 'message_content' => $message, 'created_at' => $currentTime, 'updated_at' => $currentTime]);
}
function getChatMessages($user1, $user2)
{
 $query = "SELECT users_from.id, users_from.firstname, users_from.lastname, msg.from_user_id, msg.to_user_id, message_content, msg.created_at, msg.is_read, msg.read_at
FROM messages msg
LEFT JOIN users users_from
ON  msg.from_user_id = users_from.id
LEFT JOIN users users_to
ON msg.to_user_id = users_to.id
WHERE 
(
(msg.to_user_id = $user2 AND msg.from_user_id = $user1)
OR
(msg.to_user_id = $user1 AND msg.from_user_id = $user2)
)
 ORDER BY msg.created_at DESC ";
     return sqlResult($query);
}
function setAsRead($userId, $friendId)
{
    $currentTime = date("Y-m-d H:i:s");
    $target = "from_user_id = '$friendId' AND to_user_id = '$userId' AND is_read = '0'";
    $params = "is_read = '1', read_at = '$currentTime'";
    sqlUpdateMultiple('messages', $params, $target);
}
function getComments($post){

    $query = "SELECT cmt.id, cmt.parent_id, cmt.post_id, cmt.user_id, usr.firstname, usr.lastname, cmt.content, cmt.created_at  
FROM comments cmt
LEFT JOIN users usr
ON cmt.user_id = usr.id
LEFT JOIN posts pst 
ON cmt.post_id = pst.id
WHERE cmt.post_id = $post
ORDER BY cmt.created_at;";

   return sqlResult($query);


}

function buildTree(array $comments, $parentId = null) {
    $branch = [];
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parentId) {
            $children = buildTree($comments, $comment['id']);
            if ($children) {
                $comment['replies'] = $children;
            }
            $branch[] = $comment;
        }
    }
    return $branch;
}

function renderComments($commentsTree) {
    $html = '<ul>';
    foreach ($commentsTree as $comment) {
        $html .= '<li>';
        $html .= '<strong>' . $comment['firstname'] .' '. $comment['lastname'] . '</strong><br><sup>'.$comment['created_at'].'</sup><br> ' . $comment['content'];
        if (!empty($comment['replies'])) {
            $html .= renderComments($comment['replies']);
        }
        $html .= '<br></li> ';
    }
    $html .= '</ul>';
    return $html;
}
/*___________________________________Commit from 21.03.25_________________________________*/
function comment($post_id, $content, $user)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('comments', ['post_id' => $post_id, 'user_id' => $user, 'content' => $content, 'created_at' => $currentTime, 'updated_at' => $currentTime]);
}

function replyTo($post_id, $comment_id, $content, $user)
{
    $currentTime = date("Y-m-d H:i:s");
    sqlInsert('comments', ['parent_id' => $comment_id, 'post_id' => $post_id, 'user_id' => $user, 'content' => $content, 'created_at' => $currentTime, 'updated_at' => $currentTime]);

}
/*_______________________________________________________________________________________*/
function checkPrivateAccount($userid) {
    $query = "SELECT private_account FROM users WHERE id = '$userid'";
    return sqlResult($query);
}

function checkPassword($userid) {
    $query = "SELECT password FROM users WHERE id = '$userid'";
    return sqlResult($query);
}
function checkDetails($userid)
{
 $query = "SELECT username, email FROM users WHERE id != '$userid'";
 return sqlResult($query);
}