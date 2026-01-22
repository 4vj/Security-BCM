<?php
/** @file intranet/index.php
 * Index for the intranet. Users need to logon using BasicAuth
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * Show the user's DN and all group memberships
 */

include_once "./intranet/utils/ldap_constants.inc.php";
include_once "./intranet/utils/ldap_support.inc.php";
include_once "./intranet/utils/sql_support.php";

/**
 * Function to show info on the logged in user
 * @return void
 * @throws Exception
 */
$email = "";
$voornaam = "";
$achternaam = "";
$straatnaam = "";
$huisnummer = "";
$postcode = "";
$plaatsnaam = "";
$gemeente = "";
$regio = "";
$provincie = "";
if(!empty($_POST)){
    $email = $_POST['username'];
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    $straatnaam = $_POST['straatnaam'];
    $huisnummer = $_POST['huisnummer'];
    $postcode = $_POST['postcode'];
    $plaatsnaam = $_POST['plaatsnaam'];
    $gemeente = $_POST['gemeente'];
    $regio = $_POST['regio'];
    $provincie = $_POST['provincie'];
}
function checkuser(): void
{
    try {
        $lnk = ConnectAndCheckLDAP();
        assert($lnk != false);
    }
    catch (Exception $ex) {
        die($ex->getMessage());
    }

    $username = $_POST['username'];
    $sn = $_POST['achternaam'];
    $givenName = $_POST['voornaam'];
    $userPassword = $_POST['password'];

// setup some compound variables based upon the input
    $cn = $username;
    $newUserDN = "cn=" . $cn . "," . USERS_INTERN_DN;
    try {
        CreateNewUserLdap($lnk, $newUserDN, $cn, $sn, $username, $givenName);
    } catch (Exception $exception) {
        echo $exception;
        // FIXME: do something with the exception;
    }
    echo "Gebruiker toegevoegd!\n";

    // Now create a new password.
    try {
        $password = SetPassword($lnk, $newUserDN, "$userPassword");
    } catch (Exception $exception) {
        die ($exception->getCode() . ":" . $exception->getMessage());
    }
    $groupDN = 'cn=klanten, ou=groups,'.BASE_DN;
    try {
        AddUserToGroup($lnk, $groupDN, $newUserDN);
    } catch (Exception $exception) {
        die ($exception->getCode() . ":" . $exception->getMessage());
    }
    // LDAP-verbinding sluiten
    ldap_close($lnk);
    $klantid =  (int) round((microtime(true) * 100000000) * (rand(10, 30) / 10));
    $klantnummer = (int) round((microtime(true) * 100000000) * (rand(10, 30) / 10));
    $adresid = (int) round((microtime(true) * 100000000) *(rand(10, 30) / 10));



    $straatnaam = $_POST['straatnaam'];
    $huisnummer = $_POST['huisnummer'];
    $postcode = $_POST['postcode'];
    $plaatsnaam = $_POST['plaatsnaam'];
    $gemeente = $_POST['gemeente'];
    $provincie = $_POST['provincie'];
    $regio = $_POST['regio'];
    createaddress($adresid, $plaatsnaam, $gemeente, $provincie, $regio, $straatnaam, $huisnummer, $postcode);

    createuserSQL($klantid, $klantnummer, $givenName, $sn, $adresid);

    echo "Je krijgt straks een keer een brief en dan kan je pas verder.";
}


?>
<!doctype html>
<html lang="en">
<?php include('partials/header.php'); ?>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="#">
        <img src="/images/logo.png" width="30" height="30" alt="">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
    </ul>
</nav>
<main class="container-fluid">
        <h1>Lid worden</h1>
    <form action="signup.php" method="post">
        <label for="idUserName">Email adres</label>
        <input type="text" name="username" id="idUserName" value="<?php echo $email; ?>">
        <br>
        <label for="idVoornaam">Voornaam</label>
        <input type="text" name="voornaam" id="idVoornaam" value="<?php echo $voornaam?>">
        <br>
        <label for="idAchternaam">Achternaam</label>
        <input type="text" name="achternaam" id="idAchternaam" value="<?php echo $achternaam?>">
        <br>
        <label for="idWachtwoord">Wachtwoord</label>
        <input type="password" name="password" id="idPassword"">
        <p><small class="form-text text-muted">Wachtwoord moet hoofdletter, kleine letter, een cijfer en een speciaal teken bevatten en minimaal 8 karakters lang zijn.</small></p>
        <label for="idStraatnaam">Straatnaam</label>
        <input type="text" name="straatnaam" id="idStraatnaam" value="<?php echo $straatnaam?>">
        <br>
        <label for="idHuisnummer">Huisnummer</label>
        <input type="text" name="huisnummer" id="idHuisnummer" value="<?php echo $huisnummer?>">
        <br>
        <label for="idPostcode">Postcode</label>
        <input type="text" name="postcode" id="idPostcode" value="<?php echo $postcode?>">
        <br>
        <label for="idPlaatsnaam">Plaatsnaam</label>
        <input type="text" name="plaatsnaam" id="idPlaatsnaam" value="<?php echo $plaatsnaam?>">
        <br>
        <label for="idGemeente">Gemeente</label>
        <input type="text" name="gemeente" id="idGemeente" value="<?php echo $gemeente?>">
        <br>
        <label for="idProvincie">Pronvincie</label>
        <input type="text" name="provincie" id="idProvincie" value="<?php echo $provincie?>">
        <br>
        <label for="idRegio">Regio</label>
        <input type="text" name="regio" id="idRegio" value="<?php echo $regio?>">
        <br>
        Accepteer de <a href="privacy.php">privacy voorwaarden</a>
        <input type="checkbox" id="accepteerVoorwaarden" name="accepteerVoorwaarden">
        <br>
        <?php
        function checkKlant(): void {
            $username = $_POST['username'];
            if(!filter_var($username, FILTER_VALIDATE_EMAIL)){
                echo "Vul een geldig e-mailadres in!";
                return;
            }
// LDAP-verbinding opzetten
            try{
                $lnk = ConnectAndCheckLDAP();
                assert($lnk != false);
            }
            catch(Exception $ex){
                die($ex->getMessage());
            }

// Zoeken naar de gebruiker in de LDAP-directory
            if(CheckIfUserExists($lnk, $username)){
                echo "Gebruiker bestaat al!";
                ldap_close($lnk);
                return;
            }
            $voorwaarden = $_POST['accepteerVoorwaarden'];
            if (!$voorwaarden) {
                echo 'Accepteer de privacy voorwaarden.';
                return;
            }
            $givenName = $_POST['voornaam'];
            if(strlen($givenName) == 0){
                echo 'Vul een voornaam in!';
                return;
            }
            $sn = $_POST['achternaam'];
            if(strlen($sn) == 0){
                echo 'Vul een achternaam in!';
                return;
            }
            $password = $_POST['password'];
            if(!preg_match('@[A-Z]@', $password)){
                echo 'Wachtwoord moet een hoofdletter bevatten!';
                return;
            }
            if(!preg_match('@[a-z]@', $password)){
                echo 'Wachtwoord moet een kleine letter bevatten!';
                return;
            }
            if(!preg_match('@[0-9]@', $password)){
                echo 'Wachtwoord moet een nummer bevatten!';
                return;
            }
            if(!preg_match('@[^\w]@', $password)){
                echo 'Wachtwoord moet een speciaal karakter bevatten!';
                return;
            }
            $straatnaam = $_POST['straatnaam'];
            if(strlen($straatnaam) == 0) {
                echo 'Vul je straatnaam in.';
                return;
            }
            $huisnummer = $_POST['huisnummer'];
            if(strlen($huisnummer) == 0) {
                echo 'Vul je huisnummer in.';
                return;
            }
            $postcode = $_POST['postcode'];
            if(strlen($postcode) == 0) {
                echo 'Vul je postcode in.';
                return;
            }
            $plaatsnaam = $_POST['plaatsnaam'];
            if(strlen($plaatsnaam) == 0) {
                echo 'Vul je plaatsnaam in.';
                return;
            }
            $gemeente = $_POST['gemeente'];
            if(strlen($gemeente) == 0) {
                echo 'Vul je gemeente in.';
                return;
            }
            $regio = $_POST['regio'];
            if(strlen($regio) == 0) {
                echo 'Vul je regio in.';
                return;
            }
            $provincie = $_POST['provincie'];
            if(strlen($provincie) == 0) {
                echo 'Vul je provincie in.';
                return;
            }
            checkuser();
        }
        if(array_key_exists('checkklant',$_POST)){
            checkKlant();
        }
        ?>
        <br>
        <button type="submit" name="checkklant" id="checkklant">Verzenden</button>
    </form>
</main>
<?php include_once './partials/footer.php'; ?>
</body>
</html>