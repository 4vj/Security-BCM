<?php
?>
<!doctype html>
<html lang="en">
<?php include('partials/header.php');
?>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
        <a class="navbar-brand" href="#">
            <img src="/images/logo.png" width="30" height="30" alt="">
        </a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="/intranet" class="nav-link">Login</a></li>
        </ul>
    </nav>
<main class="container-fluid">
    <article class="main">
        <header><h1>Welkom bij onze website</h1></header>
        <section>
            Dit is onze super coole website.
        </section>

        <h2>Wordt nu lid!</h2>
        <a href="signup.php"><button>Lid worden</button></a>
    </article>
</main>
<? include_once './partials/footer.php'; ?>
</body>
</html>