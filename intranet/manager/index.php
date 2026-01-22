<html>
<?php

use LDAP\Result;

include('../../partials/header.php');
include('../../partials/nav-bar.php');
if(!array_intersect([2, 8, 12, 17, 21, 25,], $_SESSION["permissions"])){
    header('Location: /404.php');
    die();
}
$managerGroupList = array();
try{
    $lnk = ConnectAndCheckLDAP();
    assert($lnk != false);
}
catch(Exception $ex){
    die($ex->getMessage());
}
try {
    $temp = GetAllLDAPGroups($lnk);
} catch (Exception $ex) {
    die($ex->getMessage());
}
//    var_dump($temp);
foreach ($temp as $group){

    if($group['id'] == 2 && in_array(2, $_SESSION["permissions"])){
        $managerGroupList[] = $group;
    }
    elseif($group['id'] == 4 && in_array(8, $_SESSION["permissions"])){
        $managerGroupList[] = $group;
    }
    elseif($group['id'] == 6 && in_array(12, $_SESSION["permissions"])){
        $managerGroupList[] = $group;
    }
    elseif($group['id'] == 8 && in_array(17, $_SESSION["permissions"])){
        $managerGroupList[] = $group;
    }
    elseif($group['id'] == 10 && in_array(21, $_SESSION["permissions"])){
        $managerGroupList[] = $group;
    }
    elseif($group['id'] == 12 && in_array(25, $_SESSION["permissions"])){
        $managerGroupList[] = $group;
    }
}
$_SESSION["managerGroups"] = $managerGroupList;
?>
<body>
<h1>Manager pagina</h1>
<!--<h2>Alleen voor coole mensen dus: --><!--</h2>-->
<form id="L" method="post">
    <select name="role">
        <?php
        foreach ($managerGroupList as $group) {
        ?>
            <option value="<?php echo $group["id"] ?>"> <?php echo str_replace(",", "",$group["dn"]) ?></option>
        <?php
        }
        ?>
    </select>
    <input type="submit" name="Submit" value="Submit">
</form>
<?php
if(isset($_POST['role'])) {
    echo "Selected Role: ".htmlspecialchars($_POST['role']);
}
?>
<hr/>
<a href="/intranet/manager/createNewUser.php"><button>Nieuwe gebruiker maken</button></a>
<br/>
<table class="table">
    <thead>
    <tr>
        <th scope="col">klantennummer</th>
        <th scope="col">Email-adres</th>
        <th scope="col">Voornaam</th>
        <th scope="col">Achternaam</th>
    </tr>
    </thead>
    <tbody>
    <?php
    require_once('../utils/ldap_constants.inc.php'); // Zorg ervoor dat dit bestand correct is opgenomen in je script
    require_once('../utils/ldap_support.inc.php');

    // LDAP-verbinding opzetten

    // Zoeken naar alle gebruikers in de LDAP-directory
    $groupDN = 'cn=ictmedewerker,ou=groups,dc=ldap,dc=energy,dc=local';
    $searchFilter = "(&(objectClass=inetOrgPerson)(!(cn=webuserldap)))"; // Filter om alle medewerkers te krijgen
    $searchResult = ldap_search($lnk, BASE_DN, $searchFilter, ['uid', "cn", 'givenname', 'sn',]);
    if (!$searchResult instanceof Result) {
        die("Fout bij zoeken naar gebruikers");
    }

    $entries = ldap_get_entries($lnk, $searchResult);
    // Controleren en weergeven van alle uid-waarden
    if ($entries['count'] > 0) {
        for ($i = 0; $i < $entries['count']; $i++) {
            $username = $entries[$i]['uid'][0];
    ?>
            <tr>
                <!--            <th scope="row">1</th>-->
                <?php if ( $_SERVER["AUTHENTICATE_UID"] === $entries[$i]['uid'][0]): ?>
                    <th><?php echo $entries[$i]['uid'][0]?></th>
                    <th><?php echo $entries[$i]['cn'][0]?></th>
                    <th><?php echo $entries[$i]['givenname'][0]?></th>
                    <th><?php echo $entries[$i]['sn'][0]?></th>
                    <td></td>
<!--                    --><?php //echo "<td><a href='editUser.php?uid=$username'><button>Bewerken</button></a></td>"; ?>
                <?php else: ?>
                    <td><?php echo $entries[$i]['uid'][0]?></td>
                    <td><?php echo $entries[$i]['cn'][0]?></td>
                    <td><?php echo $entries[$i]['givenname'][0]?></td>
                    <td><?php echo $entries[$i]['sn'][0]?></td>
                    <?php echo "<td><a href='editUser.php?uid=$username'><button>Bewerken</button></a></td>"; ?>
                <?php endif; ?>
            </tr>
    <?php
        }
    } else {
        echo "Geen gebruikers gevonden.";
    }

    // LDAP-verbinding sluiten
    ldap_close($lnk);
    ?>
    </tbody>
</table>
<br/>
<a href="/intranet/manager/createNewUser.php"><button>Nieuwe gebruiker maken</button></a>
<?php include_once '../../partials/footer.php'; ?>
</body>
</html>