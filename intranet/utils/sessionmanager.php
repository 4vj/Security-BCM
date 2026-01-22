<?php
//include $_SERVER['DOCUMENT_ROOT'] . "/database/constants.inc.php";
use LDAP\Connection;
include_once $_SERVER['DOCUMENT_ROOT'] . "/intranet/utils/ldap_constants.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/intranet/utils/ldap_support.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/intranet/utils/sql_support.php";
session_start();
function getRolesAndPerms(): Connection {
    $_SESSION["permissions"] = array();
    $_SESSION["roles"] = array();
    try {
        $lnk = ConnectAndCheckLDAP();
    } catch (Exception $ex) {
        die($ex->getMessage());
    }
    $userDN = GetUserDNFromUID($lnk, $_SERVER["AUTHENTICATE_UID"]);
    if ($userDN != null) {

        $groups = GetAllLDAPGroupMemberships($lnk, $userDN);
        if(!empty($groups)) {
            $_SESSION["roles"] = $groups;
            $permissions = getPermissions($groups);
            if(!empty($permissions)){
                $_SESSION["permissions"] = $permissions;
            }
        }
    }
    return $lnk;
}

if (session_status() == PHP_SESSION_NONE  || !array_key_exists("user" ,$_SESSION) || $_SESSION["user"] != $_SERVER["AUTHENTICATE_UID"] || (time() - $_SESSION["receiveTime"]) > (1 * 60)) {
    if(session_status() == PHP_SESSION_NONE ) {
        session_start();
    }
    $_SESSION["user"] = $_SERVER["AUTHENTICATE_UID"];
    $_SESSION["userName"] = "gebruiker";
    $_SESSION["receiveTime"] = time();
    $lnk = getRolesAndPerms();
    $temp = getklantName($lnk, $_SESSION["user"]);
    $_SESSION["userName"] = $temp[0]["givenname"][0];
}

//if(in_array(1, $_SESSION["roles"])){
//            try {
//                $lnk = ConnectAndCheckLDAP();
//            } catch (Exception $ex) {
//                die($ex->getMessage());
//            }
//            echo "klant id";
//            $_SESSION['uid'] = getklantid($lnk, $_SESSION["user"]);
//            // LDAP-verbinding sluiten
//            ldap_close($lnk);
//        }
//    elseif((time() - $_SESSION["receiveTime"]) > (0.5 * 60)){
//        getRolesAndPerms();
//        $_SESSION["receiveTime"] = time();
//        var_dump(time() - $_SESSION["receiveTime"]);
//        echo time();
//    }
//    echo $_SESSION["roles"][0];

