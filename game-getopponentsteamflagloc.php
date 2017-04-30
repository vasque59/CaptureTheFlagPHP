<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
getOtherTeamFlagLoc($_GET['teamid']);

function getOtherTeamFlagLoc($teamid) {
    $pdo = pdo_connect();

    // Grab the other teams id
    $otherTeamId = $teamid == 1 ? 2 : 1;

    // Grab the other teams flag location
    $sql =<<<SQL
    SELECT flagLat, flagLong from Team where id=$otherTeamId
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();

    $row = $otherflagLat = $statement->fetch();
    $otherflagLat = $row['flagLat'];
    $otherflagLong = $row['flagLong'];
    //Return the other teams flags lat and long
    echo '<game status="yes" msg="' . $otherflagLat . ',' . $otherflagLong . '"/>';
    exit;

}