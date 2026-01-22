<html>
<?php include('../../partials/header.php');
      include('../../partials/nav-bar.php');
if(!array_intersect([3, 9, 13, 18, 22, 26,], $_SESSION["permissions"])){
    header('Location: /404.php');
    die();
}
include_once('../utils/sql_support.php');

/**
 * Function to show info on the logged in user
 * @return void
 * @throws Exception
 */
$email = "";
$voornaam = "";
$achternaam = "";
if(!empty($_POST)){
    $email = $_POST['username'];
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
}
function createuser(): void {
    try{
        $lnk = ConnectAndCheckLDAP();
    }
    catch(Exception $ex){
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
    $medewerker = $_SERVER['AUTHENTICATE_UID'];
    $actie = 'Account voor '. $username. ' gemaakt.';
    addtoauditlog($medewerker, $actie);

    // Now create a new password.
    try {
        $password = SetPassword($lnk, $newUserDN, "$userPassword");
    } catch (Exception $exception) {
        die ($exception->getCode() . ":" . $exception->getMessage());
    }

    // Add user to a certain group ("CN=websiteusers")
    $groupDN = 'cn=medewerker,ou=groups,'.BASE_DN;
    try {
        AddUserToGroup($lnk, $groupDN, $newUserDN);
    } catch (Exception $exception) {
        die ($exception->getCode() . ":" . $exception->getMessage());
    }
    // LDAP-verbinding sluiten
    ldap_close($lnk);

    echo "Gelukt!";
}


?>
<main class="container-fluid">
    <article>
        <section>
            <h1>Nieuwe gebruiker aanmaken</h1>
            <hr/>
            <P>Gebruik onderstaande formulier om een nieuwe gebruiker aan te maken. De afhandeling van het aanmaken van
                deze gebruiker vindt plaats via het script 'createNewUser.php'.
            </P>
            <form action="createNewUser.php" method="post">
                <label for="idUserName">Email adres</label>
                <input type="text" name="username" id="idUserName" value="<?php echo $email; ?>">
                <br/>
                <label for="idVoornaam">Voornaam</label>
                <input type="text" name="voornaam" id="idVoornaam" value="<?php echo $voornaam?>">
                <br/>
                <label for="idAchternaam">Achternaam</label>
                <input type="text" name="achternaam" id="idAchternaam" value="<?php echo $achternaam?>">
                <br/>
                <label for="idWachtwoord">Wachtwoord</label>
                <input type="password" name="password" id="idPassword"">
                <br/>


                <?php
                function checkUser(): void {
                    $username = $_POST['username'];
                    if(!filter_var($username, FILTER_VALIDATE_EMAIL)){
                        echo"Vul een geldig email adres in!";
                        return;
                    }
// LDAP-verbinding opzetten
                    try{
                        $lnk = ConnectAndCheckLDAP();
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
                    $givenName = $_POST['voornaam'];
                    if(strlen($givenName) == 0){
                        echo 'Vul een voornaam in!';
                        return;
                    }
                    $sn = $_POST['achternaam'];
                    if(strlen($sn) == 0){
                        echo 'Vul een acthernaam in!';
                        return;
                    }
                    $password = $_POST['password'];
                    if(!preg_match('@[A-Z]@', $password)){
                        echo 'Wachtwoord moet een hoofdletter bevatten!';
                        return;
                    }
                    if(!preg_match('@[a-z]@', $password)){
                        echo 'Wachtwoord moet een kleineletter bevatten!';
                        return;
                    }
                    if(!preg_match('@[0-9]@', $password)){
                        echo 'Wachtwoord moet een nummer bevatten!';
                        return;
                    }
                    if(!preg_match('@[^\w]@', $password)){
                        echo 'Wachtwoord moet een speciaal karakter bevatten!';
                    }
                    else {
                        createuser();
                    }
                }
                if(array_key_exists('checkpws',$_POST)){
                    checkUser();
                }
                ?>
                <br/>
                <button type="submit" name="checkpws" id="checkpws">Opslaan</button>
            </form>
        </section>
    </article>
</main>
<?php include_once '../../partials/footer.php'; ?>
</body>
</html>
