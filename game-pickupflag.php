<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
pickUpFlag($_GET['teamid']);

function pickUpFlag($teamid) {
//    echo "<p>$flagLat</p><p>$flagLong</p><p>$teamid</p>";
    $pdo = pdo_connect();
    $sql =<<<SQL
    UPDATE Team SET isFlagPickedUp=1 WHERE color=$teamid
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();

}