<?php
include "../database/constants.inc.php";
include "utils/ldap_support.inc.php";
include "utils/ldap_constants.inc.php";
//haal klanten uit DB
$db = new PDO(MYSQL_DSN, DB_USERNAME, DB_PASSWORD);
$klanten = $db->prepare("SELECT * FROM tbl_klanten limit 11755 offset 5584;");
$klanten->execute();
try{
    $lnk = ConnectAndCheckLDAP();
}
catch(Exception $ex){
    die($ex->getMessage());
}
$statisticsJson = file_get_contents("https://www.1secmail.com/api/v1/?action=genRandomMailbox&count=500");
$email = json_decode($statisticsJson);
$getal = -1;
while ($result = $klanten->fetch()) {

    while (true) {
        $getal++;
        if($getal >= 500){
            $statisticsJson = file_get_contents("https://www.1secmail.com/api/v1/?action=genRandomMailbox&count=500");
            $email = json_decode($statisticsJson);
            $getal = 0;
        }
        if (!CheckIfUserExists($lnk, $email[$getal])) {
            break;
        }
    }
    $cn = $email[$getal];
    $sn = $result[2];
    if (is_null($sn) || $sn === '' || empty($sn) || ctype_space($sn)){
        $sn = 'kut flikker die onze code stuk maakt';
    }
    $uid = $result[1];
    $givenName = $result[3];
    echo "givename in script: " . $givenName . "\n";
    if (is_null($givenName) || $givenName === '' || empty($givenName) || ctype_space($givenName)){
        $givenName = 'kut flikker die onze code stuk maakt';
    }
    echo "hoi";
    $lnk = ConnectAndCheckLDAP();
    echo "hoi";
    CreateNewUser($lnk, "cn=$cn,". USERS_INTERN_DN,$cn,$sn,$uid,$givenName);
    SetPassword($lnk, "cn=$cn,". USERS_INTERN_DN, "%^&asdfghjkl*()");
    AddUserToGroup($lnk,"cn=klanten,ou=groups,".BASE_DN ,"cn=$cn,". USERS_INTERN_DN);
    AddUserToGroup($lnk,"cn=allusers,ou=groups,".BASE_DN ,"cn=$cn,". USERS_INTERN_DN);
// LDAP-verbinding sluiten

}
ldap_close($lnk);
?>