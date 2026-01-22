<html>
<?php use LDAP\Result;

include('../../partials/header.php'); ?>
<?php include('../../partials/nav-bar.php'); ?>
<?php
if(!array_intersect([3, 9, 13, 18, 22, 26,], $_SESSION["permissions"])){
    header('Location: /404.php');
    die();
}
include_once "../utils/ldap_constants.inc.php";
include_once "../utils/ldap_support.inc.php";
include_once "../utils/sql_support.php";
?>
<body>
<main class="container-fluid">
    <h2>Bewerk gebruikersgegevens</h2>
    <hr>
    <?php

    // Controleer of de uid is opgegeven in de queryparameters
    if (isset($_GET['uid'])) {
        $uid = $_GET['uid'];

        // LDAP-verbinding opzetten
        try{
            $lnk = ConnectAndCheckLDAP();
        }
        catch(Exception $ex){
            die($ex->getMessage());
        }

        // Zoeken naar de specifieke gebruiker in de LDAP-directory
        $searchFilter = "(&(objectClass=inetOrgPerson)(uid=$uid))";
        $searchResult = ldap_search($lnk, BASE_DN, $searchFilter);
        if (!$searchResult instanceof Result) {
            die("Fout bij zoeken naar gebruiker");
        }

        $entries = ldap_get_entries($lnk, $searchResult);

        // Controleren of de gebruiker is gevonden
        if ($entries['count'] > 0) {
            $username = $entries[0]['uid'][0];
//            echo $entries[0]['cn'][0];
            // Verwerk het formulier wanneer het wordt ingediend
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Voer hier de bewerking van gebruikersgegevens uit op basis van het formulier

                // Voorbeeld: Update de 'givenName' en 'sn' velden
                $newGivenName = $_POST['givenname'];
                $newLastName = $_POST['sn'];

                $ldapEntry = [
                    'givenname' => $newGivenName,
                    'sn' => $newLastName
                ];
                if(ldap_modify($lnk, "cn={$entries[0]['cn'][0]},". USERS_INTERN_DN, $ldapEntry)){
                    $medewerker = $_SERVER['AUTHENTICATE_UID'];
                    $actie = 'Gegevens van '. $uid. ' bewerkt.';
                    addtoauditlog($medewerker, $actie);
                    echo "Gebruikersgegevens bijgewerkt!";
                    header('Location: ./index.php');
                } else {
                    echo "jammer! het is niet gelukt.";
                }
;

            }

            // Toon het bewerkingsformulier
            echo "<p><strong>Gebruikersnaam:</strong> $username</p>";
            echo "<form method='post'>";
            echo "<label for='givenname'>Voornaam:</label>";
            echo "<input type='text' name='givenname' value='{$entries[0]['givenname'][0]}'><br>";
            echo "<label for='sn'>Achternaam:</label>";
            echo "<input type='text' name='sn' value='{$entries[0]['sn'][0]}'><br>";
            // Voeg hier andere bewerkbare velden toe op basis van je LDAP-schema
            echo "<input type='submit' value='Opslaan'>";
            echo "</form>";

        } else {
            echo "Gebruiker niet gevonden.";
        }

        // LDAP-verbinding sluiten
        ldap_close($lnk);

    } else {
        echo "Geen gebruikersnaam (uid) opgegeven.";
    }
    ?>
</main>
<?php include_once '../../partials/footer.php'; ?>
</body>
</html>
