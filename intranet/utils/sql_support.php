<?php
// FIXME: remove plaintext passwords & databasename
function SQLconnect(): PDO {
    $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/../configs/database_config.ini");

// Haal de databaseconfiguraties op
    $db_host = $config["host"];
    $db_port = $config["port"];
    $db_user = $config["user"];
    $db_password = $config["password"];
    $db_name = $config["name"];  ///< the password to connect to the database
    return new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_password); ///< make the connection
}

function getuserinfo(string $uid): mixed {
    $connection = SQLconnect();
    $SQL = 'select * from tbl_klanten where k_klantnummer = :uid';

    $statement = $connection->prepare($SQL);
    $statement->execute(['uid' => $uid]);

    $firstRecord = $statement->fetch();
    // close connection
    $connection = null;
    $statement = null;
    return $firstRecord;
}

function getadresinfo(string $adresid): mixed {
    $connection = SQLconnect();
    $SQL = 'select * from tbl_adressen where a_idadres = :adresid';
    $statement = $connection->prepare($SQL);
    $statement->execute(['adresid' => $adresid]);

    $firstRecord = $statement->fetch();

    // close connection
    $connection = null;
    $statement = null;
    return $firstRecord;
}

function getmetertelwerkid(mixed $adresid, string $type): mixed {
    $connection = SQLconnect();

    $SQL = 'select m_idmeter from tbl_meters where m_fk_idadres = :adresid';
    $statement = $connection->prepare($SQL);
    $statement->execute(['adresid' => $adresid]);

    $firstRecord = array();
    while ($result = $statement->fetch()) {
        $firstRecord[] = $result;
    }
    if ($type == 'Elektriciteit') {
        $mid = $firstRecord[0][0];
    }
    else {
        $mid = $firstRecord[1][0];
    }

    $SQL = 'select * from tbl_meter_telwerken where mt_fk_idmeter = :mid';
    $statement = $connection->prepare($SQL);
    $statement->execute(['mid' => $mid]);

    $firstRecord = $statement->fetch();

    // close connection
    $connection = null;
    $statement = null;
    return $firstRecord;
}

function getmeterstanden(mixed $metertelwerkid): array {
    $connection = SQLconnect();
    $SQL = 'SELECT * FROM tbl_meters_standen where ms_fk_idmetertelwerk = :metertelwerkid';
    $statement = $connection->prepare($SQL);
    $statement->execute(['metertelwerkid' => $metertelwerkid]);
    $allRecords = $statement->fetchAll();

    // close connection
    $connection = null;
    $statement = null;
    return $allRecords;
}

function createuserSQL(int $klantid, int $klantnummer, string $voornaam, string $achternaam, int $adresid): mixed {
    $connection = SQLconnect();
    $SQL = 'insert into tbl_klanten (k_idklant, k_klantnummer, k_achternaam, k_voornaam, k_fk_idadres) values (:klantid, :klantnummer, :achternaam, :voornaam, :adresid)';
    $statement = $connection->prepare($SQL);
    $statement->execute(['klantid' => $klantid, 'klantnummer' => $klantnummer, 'achternaam' => $achternaam, 'voornaam' => $voornaam, 'adresid' => $adresid]);

    $firstRecord = $statement->fetch();

    $SQL = 'insert into PrivacyAgree (k_idklant, dateagree) values (:klantid, :date)';
    $statement = $connection->prepare($SQL);
    $statement->execute(['klantid' => $klantid, 'date' => date('Y-m-d')]);

    // close connection
    $connection = null;
    $statement = null;
    return $firstRecord;
}

function createaddress(int $adresid, string $a_plaatsnaam, string $a_gemeente, string $a_provincie, string $a_regio, string $a_straatnaam, string $a_huisnummer, string $a_postcode): mixed {
    $connection = SQLconnect();
    $SQL = 'insert into tbl_adressen (a_idAdres, a_plaatsnaam, a_gemeente, a_provincie, a_regio, a_straatnaam, a_huisnummer, a_postcode) values (:adresid, :a_plaatsnaam, :a_gemeente, :a_provincie, :a_regio, :a_straatnaam, :a_huisnummer, :a_postcode)';
    $statement = $connection->prepare($SQL);
    $statement->execute(['adresid' => $adresid, 'a_plaatsnaam' => $a_plaatsnaam, 'a_gemeente' => $a_gemeente, 'a_provincie' => $a_provincie, 'a_regio' => $a_regio, 'a_straatnaam' => $a_straatnaam, 'a_huisnummer' => $a_huisnummer, 'a_postcode' => $a_postcode]);

    $firstRecord = $statement->fetch();

    // close connection
    $connection = null;
    $statement = null;
    return $firstRecord;
}

function addtoauditlog(string $uid, string $action): void {
    $connection = SQLconnect();
    $SQL = 'insert into auditlog (uid, actie) values (:uid, :actie)';
    $statement = $connection->prepare($SQL);
    $statement->execute(['uid' => $uid, 'actie' => $action]);

    // close connection
    $connection = null;
    $statement = null;
}

function getLogs(): array {
    $connection = SQLconnect();
    $SQL = 'select * from auditlog ORDER BY tijd DESC';
    $statement = $connection->prepare($SQL);
    $statement->execute();
    $result = $statement->fetchAll();

    $connection = null;
    $statement = null;

    return $result;
}

function getPermissions(array $groups): array {
    $connection = SQLconnect();
    $groupIds = implode(',', array_map(function($group) {
        return $group['id'];
    }, $groups));
    $statement = $connection->prepare("SELECT DISTINCT PermissionId FROM `RolesPermissions` WHERE RoleId IN ($groupIds);");
    $statement->execute();
    $permissions = $statement->fetchAll(PDO::FETCH_ASSOC);
    $permissions = array_column($permissions, 'PermissionId');

    $statement = null;
    $connection = null;

    return $permissions;
}

?>
