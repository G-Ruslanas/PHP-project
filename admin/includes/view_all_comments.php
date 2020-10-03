<table class = "table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>In Response to</th>
                                    <th>Date</th>
                                    <th>Approve</th>
                                    <th>Unapprove</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                        <tbody>
               <?php
            global $connection;
            $query = "SELECT * FROM comments";
            $select_comments = mysqli_query($connection,$query);

            while ($row = mysqli_fetch_assoc($select_comments)){
            $comment_id =  escape($row['comment_id']);
            $comment_post_id =  escape($row['comment_post_id']);
            $comment_author =  escape($row['comment_author']);
            $comment_content =  escape($row['comment_content']);
            $comment_email =  escape($row['comment_email']);
            $comment_status =  escape($row['comment_status']);
            $comment_date =  escape($row['comment_date']);

            echo "<tr>";
            echo "<td>$comment_id</td>";
            echo "<td>$comment_author</td>";
            echo "<td>$comment_content</td>";
            echo "<td>$comment_email</td>";
            echo "<td>$comment_status</td>";

            $query = "SELECT * FROM posts WHERE post_id = $comment_post_id ";
            $select_post_id_query = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($select_post_id_query)){
            $post_id = $row['post_id'];    
            $post_title = $row['post_title'];
            echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
            }
            echo "<td>$comment_date</td>";  
            echo "<td><a href='comment.php?approve=$comment_id'>Approve</a></td>";
            echo "<td><a href='comment.php?unapprove=$comment_id='>Unapprove</a></td>";
            echo "<td><a href='comment.php?delete=$comment_id'>Delete</a></td>";
            echo "</tr>";       
            }
            ?>              
                        </tbody>
                             </table>
                            <?php
    
if(isset($_GET['delete'])){
    $the_comment_id = escape($_GET['delete']);
    $query = "DELETE FROM comments WHERE comment_id = {$the_comment_id} ";
    $delete_query = mysqli_query($connection, $query);
    header("Location: comment.php");
}

    
if(isset($_GET['unapprove'])){
    $the_comment_id = escape($_GET['unapprove']);
    $query = "UPDATE comments SET comment_status= 'unapproved' WHERE comment_id = '$the_comment_id'";
    $unapprove_comment_query = mysqli_query($connection, $query);
    header("Location: comment.php");
}
    
if(isset($_GET['approve'])){
    $the_comment_id = escape($_GET['approve']);
    $query = "UPDATE comments SET comment_status= 'approved' WHERE comment_id = '$the_comment_id'";
    $approve_comment_query = mysqli_query($connection, $query);
    header("Location: comment.php");
}
?>
                        