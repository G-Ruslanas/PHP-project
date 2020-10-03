<?php
global $connection;
include ("delete_modal.php");
if(isset($_POST['checkBoxArray'])){
   foreach($_POST['checkBoxArray'] as $checkBoxValue ){
       $bulk_options = $_POST['bulk_options'];

       switch($bulk_options){
           case 'published':
               $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBoxValue} ";
               $update_to_publish_status = mysqli_query($connection, $query);
               confirm($update_to_publish_status);
               break;
            case 'draft':
               $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$checkBoxValue} ";
               $update_to_draft_status = mysqli_query($connection, $query);
               confirm($update_to_draft_status);
               break;
           case 'delete':
               $query = "DELETE FROM posts WHERE post_id = {$checkBoxValue} ";
               $update_to_delete_status = mysqli_query($connection, $query);
               confirm($update_to_delete_status);
               break;
           case 'clone':
               $query = "SELECT * FROM posts WHERE post_id = {$checkBoxValue} ";
               $select_post_query = mysqli_query($connection,$query);
               while ($row = mysqli_fetch_array($select_post_query)){
                    $post_title =  $row['post_title'];
                    $post_category_id =  $row['post_category_id'];
                    $post_date =  $row['post_date'];
                    $post_authour =  $row['post_authour'];
                    $post_user =  $row['post_user'];
                    $post_status =  $row['post_status'];
                    $post_image =  $row['post_image'];
                    $post_tags =  $row['post_tags'];
                    $post_content =  $row['post_content'];
            }
                $query = "INSERT INTO posts(post_category_id, post_title, post_authour,post_user, post_date, post_image, post_content, post_tags, post_status)";
                $query .= "VALUES({$post_category_id},'{$post_title}','{$post_authour}','{$post_user}', now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}' )";
                $copy_query = mysqli_query($connection, $query);
                confirm($copy_query);
                break;
       }
  }
}
?>
<form action="" method ='post'>
    <table class = "table table-hover table-bordered">
        <div id="bulkOptionsContainer" class = "col-xs-4">
            <select class ="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>
    <div class ="col-xs-4">
        <input type="submit" name ="submit" class = "btn btn-success" value="Apply">
        <a class ="btn btn-primary" href="posts.php?source=add_post">Add New </a>
</div>
                            <thead>
                                <tr>
                                    <th><input id="selectAllBoxes" type="checkbox"></th>
                                    <th>Id</th>
                                    <th>Users</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Tags</th>
                                    <th>Comments</th>
                                    <th>Date</th>
                                    <th>View Post</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                        <tbody>
               <?php
         // $query = "SELECT * FROM posts ORDER BY post_id DESC ";
            $query = "SELECT posts.post_id, posts.post_authour, posts.post_user,posts.post_title,posts.post_category_id,posts.post_status,posts.post_image,posts.post_tags,posts.post_comment_count,posts.post_date,posts.post_views_count, ";
            $query .= "categories.cat_id, categories.cat_title FROM posts ";
            $query .= " LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC ";
            $select_posts = mysqli_query($connection,$query);

            while ($row = mysqli_fetch_assoc($select_posts)){
            $post_id =  $row['post_id'];
            $post_authour =  $row['post_authour'];
            $post_user =  $row['post_user'];
            $post_title =  $row['post_title'];
            $post_category_id =  $row['post_category_id'];
            $post_status =  $row['post_status'];
            $post_image =  $row['post_image'];
            $post_tags =  $row['post_tags'];
            $post_comment_count =  $row['post_comment_count'];
            $post_date =  $row['post_date'];
            $post_views_count =  $row['post_views_count'];
            $category_id =  $row['cat_id'];  
            $category_title =  $row['cat_title'];
            echo "<tr>";
                ?>
            <td> <input class='checkBoxes' type='checkbox' name ='checkBoxArray[]' value ='<?php echo $post_id; ?>'></td>
            <?php      
            echo "<td>$post_id</td>";
                if(!empty($post_authour)){
                    echo "<td>$post_authour</td>";
                }   else if(!empty($post_user)){
                        echo "<td>$post_user</td>";
                }
            echo "<td>$post_title</td>";
            echo "<td>{$category_title}</td>";
            echo "<td>$post_status</td>";
            echo "<td><img width ='100' src='../images/$post_image' alt ='image'></td>";
            echo "<td>$post_tags</td>";
                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                $send_comment_query = mysqli_query($connection, $query);
                $row = mysqli_fetch_array($send_comment_query);
                $comment_id = $row['comment_id'];
                $count_comments = mysqli_num_rows($send_comment_query);
            echo "<td><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";
            echo "<td>$post_date</td>";  
            echo "<td><a class = 'btn btn-primary' href='../post.php?p_id={$post_id}'>View Post</a></td>";
            echo "<td><a class = 'btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
            ?> 
            <form method="post">
                <input type="hidden"  name = "post_id" value ="<?php echo $post_id?>">
                <?php
               echo  '<td> <input  class ="btn btn-danger" type="submit" name ="delete" value ="Delete"> </td>';
                ?>
            </form>   
            <?php
            echo "<td><a href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
            echo "</tr>";       
            }
            ?>              
                        </tbody>
                             </table>
                             </form>  
                            <?php
if(isset($_POST['delete'])){
    $the_post_id = $_POST['post_id'];
    $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
    $delete_query = mysqli_query($connection, $query);
    header("Location: posts.php");
}
if(isset($_GET['reset'])){
    $the_post_id = $_GET['reset'];
    $query = "UPDATE posts SET post_views_count = 0 WHERE post_id =" . mysqli_real_escape_string($connection,$_GET['reset']) . " ";
    $reset_query = mysqli_query($connection, $query);
    header("Location: posts.php");
}
?>
<!--Warning Script for DELETE button-->
<script>
    $(document).ready(function(){
    $(".delete_link").on('click', function(){
       var id = $(this).attr("rel");
       var delete_url = "posts.php?delete="+ id +" ";
        $(".modal_delete_link").attr("href", delete_url);
        $("#myModal").modal('show');
    });
});
</script>