<?php
//$_SERVER['DOCUMENT_ROOT']
include_once __dir__ . "/../intranet/utils/sessionmanager.php"; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="#">
        <img src="/images/logo.png" width="30" height="30" alt="">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="/intranet" class="nav-link">Home</a></li>
        <?php if(array_intersect([2, 8, 12, 17, 21, 25], $_SESSION["permissions"])):?>
        <li class="nav-item"><a href="/intranet/manager" class="nav-link">Manager</a></li>
        <?php endif;?>
        <?php if(in_array(1 , $_SESSION["permissions"])): ?>
            <li class="nav-item"><a href="/intranet/klantenservice" class="nav-link">Klanten Service</a></li>
        <?php endif;?>
        <?php if(in_array(30 , $_SESSION["permissions"])): ?>
            <li class="nav-item"><a href="/intranet/backoffice" class="nav-link">Back Office</a></li>
        <?php endif;?>
        <?php if(in_array(6 , $_SESSION["permissions"])): ?>
            <li class="nav-item"><a href="/intranet/marketing" class="nav-link">Marketing</a></li>
        <?php endif;?>
        <?php if(in_array(7 , $_SESSION["permissions"])): ?>
            <li class="nav-item"><a href="/intranet/verkoop" class="nav-link">Verkoop</a></li>
        <?php endif;?>
        <?php if(in_array(16 , $_SESSION["permissions"])): ?>
            <li class="nav-item"><a href="/intranet/hrm" class="nav-link">HRM</a></li>
        <?php endif;?>
        <?php if(in_array(31 , $_SESSION["permissions"])): ?>
            <li class="nav-item"><a href="/intranet/ict" class="nav-link">ICT</a></li>
        <?php endif;?>
        <li class="nav-item end-0 position-absolute"><a href="/intranet/logout.php" class="nav-link">Logout</a></li>

    </ul>
</nav>