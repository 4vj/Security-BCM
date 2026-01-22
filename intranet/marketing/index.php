<html>
<?php
include('../../partials/header.php');
include('../../partials/nav-bar.php');

if(!in_array(6 , $_SESSION["permissions"])){
    header('Location: /404.php');
    die();
//        echo "yeey je mag hier zijn!!";
}
?>
<body>
<h1>Marketing</h1>
<? include_once '../../partials/footer.php'; ?>
</body>
</html>