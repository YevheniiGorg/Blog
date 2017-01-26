<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Admin panel</title>

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

</head>

<body>

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
                    <a href="#">Admin</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<!-- Page Content -->

<div class="container">
    <!-- Comments Form -->
</br></br></br></br>
    <div class="col-lg-8">
        <h2>ADD new Post:</h2>
        <hr></br>
      <div class="well">

        <form role="form" method="post" enctype="multipart/form-data">
            <h4>Title:</h4>
            <input class="form-control" type="text" name="title"></br></br>
            <h4>Publish Post:</h4>
            <div class="form-group">
                <textarea class="form-control" rows="8"name="content"></textarea>
            </div>
            <fieldset>
            <legend>Add photo:</legend>
                <input type="file" name="img" id="FileSelection"accept="image/jpeg,image/png,image/gif">
                 <hr>
            </fieldset>
                <button type="submit" >Save</button>

        </form>
      </div>
    </div>
    <!-- ----------------------------- -->
    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
            </br></br></br></br>
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
try {
    // Подключение к б/д
   // $dsn = "mysql:host=localhost;dbname=blog";
    $dsn = "sqlite:../blog.sqlite";
    $db = new PDO($dsn, 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//    $db = new PDO("sqlsrv:server = tcp:kucherin.database.windows.net,1433; Database = kucherinblog", "adminblog", "tel_3637842");
//    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "<p style='color: green'>connected</p>";

    $db->beginTransaction(); // начало транзакции
    if(!empty($_POST)) {
        if(isset($_POST['title']) && isset($_POST['content'])) {

            extract($_POST);

            $published_date = date('Y-m-d H:i:s');

            if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {

                $filepath = $_FILES['img']['tmp_name'];
                $filename = $_FILES['img']['name'];
                $dest = '../img/' . "$filename";
                move_uploaded_file($filepath, $dest);

                $db->exec("INSERT INTO post(title, content, published_date, published_photo) values ('$title', '$content','$published_date', '$filename')");
            }else{
             $db->exec("INSERT INTO post(title, content, published_date) values ('$title', '$content','$published_date')");
            }
        }
    }

    $db->commit(); // подтверждаем выполенение команды

}catch (PDOException $ex) {

    echo "<p style='color: red'>" . $ex->getMessage() . "</p>";
}
































