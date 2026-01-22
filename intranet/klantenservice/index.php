<html>
<?php
//ff chechen of
if(!empty($_SERVER['AUTHENTICATE_UID'])){
    include(__DIR__ . '/../../partials/header.php');
    include(__DIR__ . '/../../partials/nav-bar.php');

    if(!in_array(1 , $_SESSION["permissions"])){
        header('Location: /404.php');
        die();
//        echo "yeey je mag hier zijn!!";
    }
}

    ?>
<body>
<h1>Klantenservice</h1>
<hr/>
<br/>
<form action="index.php" method="post">
    <label for="idUserName">Klantnummer</label>
    <input type="text" name="klantid" id="klantid">
    <label for="stroom">Elektriciteit</label>
    <input type="radio" name="type" id="stroom" value="Elektriciteit" checked="checked">
    <label for="gas">Gas</label>
    <input type="radio" name="type" value="Gas" id="gas">
    <br/>
    <button type="submit" name="search" id="search">Zoek</button>
</form>
<table class="table">
    <thead>
    <tr>
        <th scope="col">klantennummer</th>
        <th scope="col">Voornaam</th>
        <th scope="col">Achternaam</th>
        <th scope="col">Id adres</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Zorg ervoor dat dit bestand correct is opgenomen in je script
    if(!empty($_SERVER['AUTHENTICATE_UID'])) {
        include_once( '../utils/sql_support.php');
    }
    function search(): void {
        $uid = $_POST['klantid'];
        if(!empty($_SERVER['AUTHENTICATE_UID'])) {
            $medewerker = $_SERVER['AUTHENTICATE_UID'];
            $actie = 'Gegevens van ' . $uid . ' opgehaald.';
            addtoauditlog($medewerker, $actie);
        }
        $searchResult = getuserinfo($uid);
        if (!$searchResult) {
            die("Fout bij zoeken naar gebruikers");
        }
        // Controleren en weergeven van alle uid-waarden
        $aid = $searchResult[4];
        assert(gettype($aid) === "string");

        ?>
        <tr>
            <!--            <th scope="row">1</th>-->
            <td><?php echo $searchResult[1]?></td>
            <td><?php echo $searchResult[3]?></td>
            <td><?php echo $searchResult[2]?></td>
            <td><?php echo $searchResult[4]?></td>
        </tr>
        </tbody>
            <?php
            $searchResult = getadresinfo($aid);
            ?>
                <br>
        <thead>
        <tr>
            <th scope="col">Plaatsnaam</th>
            <th scope="col">Gemeente</th>
            <th scope="col">Provincie</th>
            <th scope="col">Regio</th>
            <th scope="col">Straatnaam</th>
            <th scope="col">Huisnummer</th>
            <th scope="col">Postcode</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <!--            <th scope="row">1</th>-->
                <td><?php echo $searchResult[1]?></td>
                <td><?php echo $searchResult[2]?></td>
                <td><?php echo $searchResult[3]?></td>
                <td><?php echo $searchResult[4]?></td>
                <td><?php echo $searchResult[5]?></td>
                <td><?php echo $searchResult[6]?></td>
                <td><?php echo $searchResult[7]?></td>
            </tr>
        </tbody>
        <?php
        $type = $_POST['type'];
            $searchResult = getmetertelwerkid($aid, $type);
            $metertelwerkid = $searchResult[0];
            $searchResult = getmeterstanden($metertelwerkid);
        ?>
            <thead>
        <tr>
            <th scope="col">Meterstand (<?echo $type?>)</th>
            <th scope="col">Datum</th>
            <th scope="col">Tijd</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < count($searchResult); $i++) {
                ?>
        <tr>
            <td><?php echo $searchResult[$i][2]?></td>
            <td><?php echo $searchResult[$i][3]?></td>
            <td><?php echo $searchResult[$i][4]?></td>
        </tr>
    <?php
        }
    ?>
    </tbody>
    <?php
    }
    if(array_key_exists('search',$_POST)){
        search();
    }
    ?>
    </tbody>
</table>
<?php //include_once '../../partials/footer.php'; ?>
</body>
</html>