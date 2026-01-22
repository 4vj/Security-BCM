<html>
<?php include('../../partials/header.php'); ?>
<body>
<!-- if sessie niet ingelogd -->
<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="#">
        <img src="/images/logo.png" width="30" height="30" alt="">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="/intranet/klant" class="nav-link">Overzicht</a></li>
        <li class="nav-item"><a href="/intranet/klant/compare.php" class="nav-link">Vergelijk buurt</a></li>
    </ul>
</nav>
<!-- else{

<?php include('partials/nav-bar.php'); ?>
} -->
<main class="container-fluid">
    <article class="main justify-content-center">
        <h1 class="text-center">Hallo, <?=$_SERVER["AUTHENTICATE_UID"]?></h1>
        <div class="text-center">
            <button class="btn btn-primary">Gas</button>
            <button class="btn btn-outline-primary">Elektra</button>
        </div>
        <div class="d-flex align-content-center">
            <div class="col-5"></div>
            <div class="col-2 align-content-center">
                <h5>Filters</h5>
                <ul>
                    <li>A</li>
                    <li>B</li>
                    <li>C</li>
                </ul>
            </div>
            <div class="col-5"></div>
        </div>
        <div class="d-flex">
            <div class="col-4">
                <h4 class="text-center">Uw gemiddelde:</h4>
                <img src="https://cdn.discordapp.com/attachments/1181573470014951538/1214940973692624936/image.png?ex=65faf0f4&is=65e87bf4&hm=48a0ba0280693531e484f41c508e750fbd32fe3b50d1bf33cf352debd05bfc21&">
            </div>
            <div class="col-4">
                <h4 class="text-center">Uw buurt:</h4>
                <img src="https://cdn.discordapp.com/attachments/1181573470014951538/1214940973692624936/image.png?ex=65faf0f4&is=65e87bf4&hm=48a0ba0280693531e484f41c508e750fbd32fe3b50d1bf33cf352debd05bfc21&">
            </div>
            <div class="col-4">
                <h4 class="text-center">Verschil:</h4>
                <img src="https://cdn.discordapp.com/attachments/1181573470014951538/1214940973692624936/image.png?ex=65faf0f4&is=65e87bf4&hm=48a0ba0280693531e484f41c508e750fbd32fe3b50d1bf33cf352debd05bfc21&">
            </div>
        </div>
    </article>
</main>
<? include_once '../../partials/footer.php'; ?>
</body>
</html>