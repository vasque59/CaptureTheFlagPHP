<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
updateFlagLoc($_GET['flagLat'],$_GET['flagLong'],$_GET['teamid']);

function updateFlagLoc($flagLat, $flagLong, $teamid) {
//    echo "<p>$flagLat</p><p>$flagLong</p><p>$teamid</p>";
    $pdo = pdo_connect();
    $sql =<<<SQL
    UPDATE Team SET flagLat=$flagLat, flagLong=$flagLong WHERE id=$teamid
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();

}