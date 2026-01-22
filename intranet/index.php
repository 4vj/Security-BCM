<?php
include_once "./utils/sessionmanager.php";

if(in_array(29, $_SESSION["permissions"])){
    header('Location: /intranet/klant/index.php');
    die();
}
include_once "utils/ldap_constants.inc.php";
include_once "utils/ldap_support.inc.php";

?>
<html>
<?php include('../partials/header.php'); ?>
<?php include('../partials/nav-bar.php'); ?>
<?php


?>
<body>
<h1>Welkom op het super veilige deel van de website! hoi: <?php echo $_SESSION["userName"] ?></h1>

<? include_once '../partials/footer.php'; ?>
</body>
</html>
