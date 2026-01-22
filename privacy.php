<?php
/** @file index.php
 * Index for the public website
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * Show a very basis HTML Bootstrap template
 */
?>
<!doctype html>
<html lang="en">
<?php include('partials/header.php'); ?>
<body>
<!-- if sessie niet ingelogd -->
<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="#">
        <img src="/images/logo.png" width="30" height="30" alt="">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
    </ul>
</nav>
<!-- else{

<?php include('partials/nav-bar.php'); ?>
} -->
<main class="container-fluid">
    <h1>Privacy voorwaarden</h1>
    <p>bla bla bla</p>
    <p>Saai gelul</p>
</main>
<? include_once './partials/footer.php'; ?>
</body>
</html>