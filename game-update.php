<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
getStatus($_GET['flagLat'],$_GET['flagLong'],$_GET['teamid']);


function getStatus($flagLat, $flagLong, $teamid) {
//    echo "<p>$flagLat</p><p>$flagLong</p><p>$teamid</p>";
    $pdo = pdo_connect();
    $sql =<<<SQL
    UPDATE Team SET flagLat=$flagLat, flagLong=$flagLong WHERE id=$teamid
SQL;
    echo "<p>$sql</p>";
    $statement = $pdo->prepare($sql);
    $statement->execute();

}