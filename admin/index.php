<?php include "includes/admin_header.php" ?>
<!-- Navigation -->
<?php include "includes/admin_navigation.php" ?>

<?php
$post_count=count_records(get_all_user_posts());
$comment_count = count_records(get_all_post_user_comments());
$categories_count=count_records(get_all_user_categories());
$post_unapproved_comments = count_records(get_all_user_unapproved_comments());
$approved_comment_count = count_records(get_all_user_approved_comments()) ;
$post_published_count = count_records(get_all_user_published_posts());
$post_draft_count = count_records(get_all_user_draft_posts());
?>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to personal Data: <?php  echo strtoupper(get_user_name()); ?>
                        </h1>
                    </div>
                </div>
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-file-text fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                <?php echo "<div class='huge'>".$post_count."</div>"?>
                        <div>Posts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <?php echo "<div class='huge'>".$comment_count."</div>"?>
                      <div>Comments</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <?php echo "<div class='huge'>".$categories_count."</div>"?>
                        <div>Categories</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
        <?php
        ?>
        <div class = "row">
<!--SCRIPT for graph-->
      <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data', 'Count'],
            <?php
            $element_text = ['Active Posts','Draft Posts', 'Active Posts','Comments', 'Approved Comments','Pending Comments','Categories'];
            $element_count = [$post_count,$post_draft_count, $post_published_count, $comment_count,$approved_comment_count, $post_unapproved_comments,$categories_count];
            for($i=0;$i < 7; $i++){
                echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
            }
        ?>
        ]);
        var options = {
          chart: {
            title: '',
            subtitle: '',
          }
        };
        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
        <div id="columnchart_material" style="width: auto; height: 500px;"></div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
 <?php include "includes/admin_footer.php"; ?>