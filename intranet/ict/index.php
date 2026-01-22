<html>
<?php
include('../../partials/header.php');
include('../../partials/nav-bar.php');
//include_once "../sql_support.php";

if(!in_array(31, $_SESSION["permissions"])){
    header('Location: /404.php');
    die();
}
$_result = getLogs();
    // close connection
//    $connection = null;
//    $statement = null;
?>
<body>
<h1>Ict</h1>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Gebruiker</th>
        <th scope="col">Actie</th>
        <th scope="col">Tijd</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_result as $result){
    ?>
        <tr>
            <td><?php echo $result['uid']?></td>
            <td><?php echo $result['actie']?></td>
            <td><?php echo $result['tijd']?></td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>


<?php
    $connection = null;
    $statement = null;
include_once '../../partials/footer.php'; ?>
</body>
</html>