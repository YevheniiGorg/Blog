<?php
define("MaxLenthContent",330);
define("CountOnThePage",3);
$TotalPage=1;//количество страниц в блоге

if(isset($_GET['page'])){
    $page=$_GET['page'];
}else $page=1;

try {
    // Подключение к б/д
    //$dsn = "mysql:host=localhost;dbname=blog";
   // $db = new PDO($dsn, 'root');
   // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db = new PDO("sqlsrv:server = tcp:kucherin.database.windows.net,1433; Database = kucherinblog", "adminblog", "tel_3637842");
    //$db = new PDO("sqlsrv:server = tcp:kucherin.database.windows.net,1433; Database = dbblog");

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    print_r($db);
    $db->beginTransaction(); // начало транзакции

    echo "<p style='color: green'>connected </p>";


    $sql = "SELECT * FROM post ORDER BY published_date DESC";
    $pst = $db->prepare($sql);
    $pst->execute();

    class Row {}

    function Slider($page,$pst){//вывод нужных трех статтей в соотв. от номера стр.

        $row=$pst->fetchAll(PDO::FETCH_CLASS, 'Row');
        $TotalPage=ceil(count($row)/CountOnThePage);//количество страниц в блоге
        if (count($row)<=CountOnThePage){//проверка для вывода диапазона массива $pst
            $cnt=count($row);
            $start=0;
        }else{
            if ($page==1){
                $start=$page-1;
                $cnt=CountOnThePage;
            }else{
                $start=CountOnThePage*($page-1);
                $cnt=CountOnThePage*$page;
                if(count($row)<$cnt){
                    $cnt=count($row);
                }
            }
        }
        for ($i=$start;$i<$cnt;$i++)//вывод нужного диапазона
        {
            ?>
            <div id=<?php echo "{$row[$i]->id}"?>>
                <h2>
                    <a class="title"><?php echo "{$row[$i]->title}" ?></a>
                </h2>
                <p class="lead">
                <h5>by <a href="index.php">Kucherin Yevhenii</a></h5>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo "{$row[$i]->published_date}" ?></p>
                <hr>
                <a class="btn"> <img class="img-responsive" src= <?php
                    if(!empty($row[$i]->published_photo))echo "../img/"."{$row[$i]->published_photo}";
                    else echo "http://placehold.it/900x300";
                    ?> > </a>
                <hr>
                <p>
                    <?php //вывод контента длинной до 330 символов
                    $str=substr($row[$i]->content, 0, MaxLenthContent);
                    if(strlen($row[$i]->content)>MaxLenthContent) echo" $str"."...";
                    else  echo"$str" ?>
                </p>

                <a class="btn btn-primary" >Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                <hr>
            </div>
        <?php }
    return $TotalPage;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Home </title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">

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

        $(document).on('click', '.btn-primary', function () {

            var ID=$(this).parent('div').attr('id');
            //IMG=IMG.substr(IMG.indexOf("img/")+4).replace('.','%');//Hазвание фото

            var url="http://localhost/Blog/startbootstrap-blog-post-master/index.php?id="+ID
            $(location).attr('href',url);
            //$.post( "../startbootstrap-blog-post-master/index.php", { nameImg: "MyImg" } );
        })

        $(document).on('click','.title',function () {
            var ID=$(this).parent().parent('div').attr('id');
            var url="http://localhost/Blog/startbootstrap-blog-post-master/index.php?id="+ID
            $(location).attr('href',url);
        })
        $(document).on('click','.img-responsive',function () {
            var ID=$(this).parent().parent('div').attr('id');
            var url="http://localhost/Blog/startbootstrap-blog-post-master/index.php?id="+ID
            $(location).attr('href',url);
        }) 
        

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
            <a class="navbar-brand" href="./index.php">BestBlog</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="./index.php">Blog</a>
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
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <h1 class="page-header">
                Official Kucherin Blog
                <small>«The Pursuit Of Perfection»</small>
            </h1>
            <!-- First Blog Post -->
<?php

  $totalcount=Slider($page,$pst);
 ?>

            <!-- Pager -->
            <ul class="pager">
                <li class="previous">
                    <?php
                    $url='index.php?page='.($page-1);
                    if ($page!=1) echo "<a href='$url'>&larr; Older</a>";
                    ?>

                </li>
                <li class="next">
                    <?php
                    $url='index.php?page='.($page+1);
                    if($page!=$totalcount) echo "<a href='$url'>Newer &rarr;</a>";
                    ?>

                </li>
            </ul>
<?php
echo "<p><h4>Page: ";
for ($i=1;$i<$totalcount+1;$i++ ){
    if ($page==$i)echo "<a >$i&nbsp </a> ";
        else echo "<a style='font-size: small'>$i&nbsp</a> ";

}
echo "</h4></p>";

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

    <hr>

    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>MyBlog &copy; Kucherin Yevhenii 2017</p>
            </div>
            <!-- /.col-lg-12 -->
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
