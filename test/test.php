<?php
include_once "../intranet/sql_support.php";

function test_createuser() {
    createuser(2000000000, 2000000000, "Stefan", "Suk; create table test(a int);", 2000000000);
    $connection = SQLconnect();
    $connection->prepare("select * from tbl_klanten where k_klantnummer = 9999999999;")->execute();
    $statement = $connection->prepare("select table_name from information_schema.tables where table_schema = 'secbcm' and table_name = 'test';");
    $statement->execute();
    assert(count($statement->fetchAll()) == 0);
    $connection->prepare("drop table if exists test;")->execute();
    $connection->prepare("delete from tbl_klanten where k_klantnummer = 9999999999");
}

test_createuser();
