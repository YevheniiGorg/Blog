<?php

if(isset($_GET['id'])){
    file_put_contents( 'log.txt', $_GET['id']);
}
try {
    // Подключение к б/д
   // $dsn = "mysql:host=localhost;dbname=blog";
   // $db = new PDO($dsn, 'root');
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db = new PDO("sqlsrv:server = tcp:kucherin.database.windows.net,1433; Database = kucherinblog", "adminblog", "tel_3637842");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    //echo "<p style='color: green'>connected</p>";
    $db->beginTransaction(); // начало транзакции

    $sql = "SELECT * FROM post WHERE id=".$_GET['id'];
    $pst = $db->prepare($sql);
    $pst->execute();

    $sql2="SELECT * FROM comments WHERE postID=".$_GET['id'];
    $pst2=$db->prepare($sql2);
    $pst2->execute();

    class Row {};

    class Comments{};
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Post </title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-post.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery-2.0.3.min.js"></script>
</head>

<body>
<script type="text/javascript">
$(document).ready(function () {

})
</script>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../startbootstrap-blog-home-master/index.php">Start Bootstrap</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="../startbootstrap-blog-home-master/index.php">Blog</a>
                </li>
                <li>
                    <a href="../Admin/index.php">Admin</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Post Content Column -->
        <div class="col-lg-8">

            <!-- Blog Post -->

            <!-- Title -->
            <?php
    foreach ($pst->fetchAll(PDO::FETCH_CLASS, 'Row') as $row){
            ?>
            <h1><?php echo "{$row->title}"?></h1>

            <!-- Author -->
            <p class="lead">
                by <a href="../startbootstrap-blog-home-master/index.php">Start Bootstrap</a>
            </p>

            <hr>

            <!-- Date/Time -->
            <p><span class="glyphicon glyphicon-time"></span> <?php echo "{$row->published_date}" ?></p>

            <hr>

            <!-- Preview Image -->
            <img class="img-responsive" src=<?php
        if(!empty($row->published_photo))echo "../img/"."{$row->published_photo}";
        else echo "http://placehold.it/900x300";
        ?> alt="">

            <hr>
            <!-- Post Content -->
            <p class="lead"><?php echo "{$row->content}" ?></p>

            <hr>
<?php } ?>
            <!-- Blog Comments -->

            <!-- Comments Form -->
            <div class="well">
                <h4>Leave a Comment:</h4>
                <hr>
                <form role="form" method="post" >
                    <div class="form-group">
                        <h4>Your name:</h4>
                        <input type="text" name="UserName" class="form-control"></br>
                        <h4>Comment:</h4></br>
                        <textarea class="form-control" rows="3"name="Comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

            <hr>

            <!-- Posted Comments -->

            <?php

            if (isset($_POST['Comment'])){
                extract($_POST);
                $published_date = date('Y-m-d H:i:s');
                $id=$_GET['id'];

                $db->exec("INSERT INTO comments(nameuser, comment_time, content, postID) values ('$UserName', '$published_date','$Comment',$id)");

                $db->commit(); // подтверждаем выполенение команды
            }

                $sql2="SELECT * FROM comments WHERE postID=".$_GET['id']." ORDER BY comment_time DESC";
                $pst2=$db->prepare($sql2);
                $pst2->execute();
                foreach ($pst2->fetchAll(PDO::FETCH_CLASS, 'Comments') as $com){
                    ?>
                    <!-- Comment -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php
                                echo "{$com->nameuser}";
                                ?>
                                <small><?php echo "{$com->comment_time}"; ?></small>
                            </h4>
                            <?php echo "{$com->content}"; ?>
                        </div>
                    </div>
                    <hr>
                    <?php
                }
             ?>
        </div>

        <!-- Blog Sidebar Widgets Column -->
        <div class="col-md-4">

            <!-- Blog Search Well -->
            <div class="well">
                <h4>Blog Search</h4>
                <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                </div>
                <!-- /.input-group -->
            </div>

            <!-- Blog Categories Well -->
            <div class="well">
                <h4>Blog Categories</h4>
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            <li><a href="#">Аномалии</a>
                            </li>
                            <li><a href="#">Вооружение</a>
                            </li>
                            <li><a href="#">Здоровье</a>
                            </li>
                            <li><a href="#">Известные личности</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            <li><a href="#">Инновации</a>
                            </li>
                            <li><a href="#">Интернет</a>
                            </li>
                            <li><a href="#">Люди</a>
                            </li>
                            <li><a href="#">Наука</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.row -->
            </div>

            <!-- Side Widget Well -->
            <div class="well">
                <h4>Side Widget Well</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
            </div>

        </div>

    </div>
    <!-- /.row -->

    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>MyBlog &copy; Kucherin Yevhenii 2017</p>
            </div>
        </div>
        <!-- /.row -->
    </footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>


<?php

}catch (PDOException $ex) {
    // $db->rollBack(); // откат изменений
    echo "<p style='color: red'>" . $ex->getMessage() . "</p>";
}
