<?php
/** @file footer.php
 * The PHP script to use as a footer showing information from the database
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * This is the very basic way of interacting with a MySQL database. Lots of stuff to be improved for security!
 */

  // FIXME: remove plaintext passwords & databasename
/*
function SQLconnect()
{
    $user = 'secbcm';       ///< the username to connect to the database
    $pass = '%^&asdfghjkl*()';    ///< the password to connect to the database
    return new PDO('mysql:host=localhost;dbname=secbcm', $user, $pass); ///< make the connection
}
$connection = SQLconnect();
  $SQL = 'SELECT version FROM tbl_configuration;';

  // FIXME: catch errors to prevent SQL-injection problems etc.
  $statement = $connection->prepare($SQL);
  $statement->execute();
  $firstRecord = $statement->fetch();
  $version =  $firstRecord["version"];

  // close connection
  $connection = null;
  $statement = null;
*/
?>

<footer style="display: flex;flex-direction: row;align-items: center;justify-content: center">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a href="/disclaimer.html" class="nav-link">Disclaimer</a></li>
            <li class="nav-item"><a href="/contact.html" class="nav-link">Contact</a></li>
            <li class="nav-item"><a href="/privacy.php" class="nav-link">Privacy</a></li>
        </ul>

    </nav>
    <p style="width:100px">Version <? echo "1000000000" ?></p>
</footer>