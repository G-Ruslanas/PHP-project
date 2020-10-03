<?php include "includes/admin_header.php" ?>
<!-- Navigation -->
<?php include "includes/admin_navigation.php" ?>

   <?php
    if(isset($_SESSION['username'])){
        global $connection;
        $username = $_SESSION['username'];
        $query = "SELECT * FROM users WHERE username ='{$username}' ";
        $select_user_profile_query = mysqli_query($connection, $query);
        while($row = mysqli_fetch_array($select_user_profile_query)){
            $user_id =  escape($row['user_id']);
            $username =  escape($row['username']);
            $user_password =  escape($row['user_password']);
            $user_firstname =  escape($row['user_firstname']);
            $user_lastname = escape($row['user_lastname']);
            $user_email =  escape($row['user_email']);
            $user_image =  escape($row['user_image']);
        }
    }
    ?>
<?php
    if(isset($_POST['edit_user'])){
        $user_firstname = escape($_POST['user_firstname']);
        $user_lastname = escape($_POST['user_lastname']);
        $username = escape($_POST['username']);
        $user_email = escape($_POST['user_email']);
        $user_password = escape($_POST['user_password']);
           $query = "UPDATE users SET ";
           $query .="user_firstname = '{$user_firstname}', ";
           $query .="user_lastname = '{$user_lastname}', ";
           $query .="username = '{$username}', ";
           $query .="user_email = '{$user_email}', ";
           $query .="user_password = '{$user_password}' ";
           $query .="WHERE username = '{$username}' ";
           $edit_user_query = mysqli_query($connection, $query);
           confirm($edit_user_query);
  }
?>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                     <h1 class="page-header">
                            Welcome to Profile
                            <small><?php echo $_SESSION['username'] ?></small>
                        </h1>
                    
                    <form action="" method = "post" enctype = "multipart/form-data">
    <div class ="form-group">
    <label for="post_authour">Firstname</label>
    <input type="text" value ="<?php echo $user_firstname;?>"class ="form-control" name="user_firstname">
    </div>
    <div class ="form-group">
    <label for="post_status">Lastname</label>
    <input type="text" value ="<?php echo $user_lastname;?>"class ="form-control" name="user_lastname">
    </div>
    <div class ="form-group">
    <label for="post_tags">Username</label>
    <input type="text"value ="<?php echo $username;?>" class ="form-control" name="username">
    </div>
    <div class ="form-group">
    <label for="post_content">Email</label>
    <input type="text" value ="<?php echo $user_email;?>" class ="form-control" name="user_email">
    </div>
     <div class ="form-group">
    <label for="post_content">Password</label>
    <input autocomple = "off" type="password" class ="form-control" name="user_password">
    </div>
    <div class ="form-group">
    <input class="btn btn-primary" type="submit" name="edit_user" value ="Update Profile">
    </div>
</form>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
        </div>
 <?php include "includes/admin_footer.php"; ?>