<?php

//--- DATABASE HELPER FUNCTIONS ---//
function redirect($location){
    header("Location:" . $location);
    exit;
}

function query($query){
    global $connection;
    $result = mysqli_query($connection,$query);
    confirm($result);
    return $result;
}

function fetchRecord($result){
    return mysqli_fetch_array($result);
}

function count_records($result){
    return mysqli_num_rows($result);
}

function escape($string){
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

function confirm($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED " . mysqli_error($connection));
    }
}

function recordCount($table){
    global $connection;
    $query = "SELECT * FROM " . $table;
    $select_all_post = mysqli_query($connection, $query);
    $result = mysqli_num_rows($select_all_post);
    confirm($result);
    return $result;
}

//--- END DATABASE HELPER FUNCTIONS ---//

//--- GENERAL HELPER FUNCTIONS ---//

function get_user_name(){
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

function get_all_user_posts(){
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()."");
}

function get_all_post_user_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=".loggedInUserId()."");
}

function get_all_user_categories(){
    return query("SELECT * FROM categories WHERE user_id=".loggedInUserId()."");
}

function get_all_user_published_posts(){
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()." AND post_status ='published'");
}

function get_all_user_draft_posts(){
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()." AND post_status ='draft'");
}

function get_all_user_approved_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=".loggedInUserId()." AND comment_status = 'approved'");
}

function get_all_user_unapproved_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=".loggedInUserId()." AND comment_status = 'unapproved'");
}

function checkStatus($table,$column,$status){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column='$status'";
    $result = mysqli_query($connection, $query);
    confirm($result);
    return mysqli_num_rows($result);
}

function checkUserRole($table, $column, $role){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column ='$role'";
    $select_all_sub_users = mysqli_query($connection, $query);
    return mysqli_num_rows($select_all_sub_users);
}

//--- END GENERAL HELPER FUNCTIONS ---//

//--- AUTHENTICATION HELPER FUNCTIONS ---//

function is_admin(){
    if (isLoggedIn()) {
        $result = query("SELECT user_role FROM users WHERE user_id=".$_SESSION['user_id']."");
        $row = fetchRecord($result);
        if ($row['user_role'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }
return false;
}

function username_exists($username){
    global $connection;
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirm($result);
    if(mysqli_num_rows($result) > 0){
        return true;
    } else {
        return false;
    }
}

function email_exists($email){
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirm($result);
    if(mysqli_num_rows($result) > 0){
        return true;
    } else {
        return false;
    }
}

function ifItIsMethod($method=null){
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}

function isLoggedIn(){
    if (!isset($_SESSION)) {
        session_start();
    }
    if(isset($_SESSION['user_role'])){
        return true;
    }
    return false;
}
//--- END AUTHENTICATION HELPER FUNCTIONS ---//

//--- BASIC HELPER FUNCTIONS ---//

function insert_categories(){
        global $connection;
        if(isset($_POST['submit'])){
        $cat_title = escape($_POST['cat_title']);

        if($cat_title =="" || empty($cat_title)){
        echo "This field should be not empty";
        } else {
        $user_id = loggedInUserId();
        $stmt = mysqli_prepare($connection,"INSERT INTO categories(cat_title,user_id) VALUES(?,?) ");
            
            mysqli_stmt_bind_param($stmt, 'si', $cat_title,$user_id);
            mysqli_stmt_execute($stmt);

        if(!$stmt){
        die('QUERY FAILED' . mysqli_error($connection));
        header("Location: categories.php");
        }
        }
             mysqli_stmt_close($stmt);
        }
}

function findAllCategories(){
    global $connection;
    $query = "SELECT * FROM categories ";
    $select_categories = mysqli_query($connection,$query);
    while ($row = mysqli_fetch_assoc($select_categories)){
        $cat_id =  escape($row['cat_id']);
        $cat_title =  escape($row['cat_title']);
        echo "<tr>";
        echo "<td>  {$cat_id}</td>";
        echo "<td>  {$cat_title}</td>";
        echo "<td>  <a href='categories.php?delete={$cat_id}'>delete</a></td>";
        echo "<td>  <a href='categories.php?edit={$cat_id}'>edit</a></td>";
        echo "</tr>";
        if(isset($_GET['delete'])){

            $the_cat_id = escape($_GET['delete']);
            $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
            $delete_query = mysqli_query($connection,$query);
            header("Location: categories.php");
        }

    }
}

function deleteCategories(){
    global $connection;
    if(isset($_GET['delete'])){
        $the_cat_id = escape($_GET['delete']);
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection,$query);
        header("Location: categories.php");
    }
}

function imagePlaceholder($image=''){
    if(!$image){
        return 'grazus.jpg';
    } else {
        return $image;
    }
}

function getPostLikes($post_id){
    $result = query("SELECT * FROM likes WHERE post_id=$post_id");
    confirm($result);
    echo mysqli_num_rows($result);
}

function userLikedThisPost($post_id = ''){
    $result =query("SELECT * FROM likes WHERE user_id=" .loggedInUserId() ." AND post_id ={$post_id}");
    confirm($result);
    return mysqli_num_rows($result) >= 1 ? true : false;
}


//---END BASIC HELPER FUNCTIONS ---//

//---END LOGIN/REGISTRATION HELPER FUNCTIONS ---//

function register_user($username, $email, $password){
    global $connection;
    $username = mysqli_real_escape_string($connection, $username);
    $email =  mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);
    $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12) );
    $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
    $query .= "VALUES('{$username}','{$email}','{$password}', 'subscriber' )";
    $register_user_query = mysqli_query($connection, $query);
    confirm($register_user_query);
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){
    if(isLoggedIn()){
        redirect($redirectLocation);
    }
}

function login_user($username, $password){
    global $connection;
    $username = trim($username);
    $password = trim($password);
    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);
    $query = "SELECT * FROM users WHERE username='{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if(!$select_user_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    while($row = mysqli_fetch_array($select_user_query)){
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
        if(password_verify($password, $db_user_password)){
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $db_user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            redirect("/cms/index.php");
        }
        else {
            return false;
        }
    }
    return true;
}

function loggedInUserId(){
    if(isLoggedIn()){
        $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username']."'");
        confirm($result);
        $user = mysqli_fetch_array($result);
        return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;
    }
    return false;
}

function users_online(){
    if(isset($_GET['onlineusers'])){
        global $connection;
        if(!$connection){
            session_start();
            include("../includes/db.php");
            $session = session_id();
            $time = time();
            $time_out_in_seconds = 05;
            $time_out = $time - $time_out_in_seconds;
            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);
            if($count == NULL){
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
            } else {
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }
            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
            echo $count_user = mysqli_num_rows($users_online_query);
        }
    }
}
//---END LOGIN/REGISTRATION HELPER FUNCTIONS ---//
users_online()
?>