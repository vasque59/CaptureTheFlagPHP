<?php
require_once "db.inc.php";

process($_GET['teamid']);

function process($teamid) {
    // Connect to the database
    $pdo = pdo_connect();

    $sql =<<<SQL
    SELECT isFlagPickedUp from Team where color=$teamid
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $isFlagPickedUp = $statement->fetch()['isFlagPickedUp'];
    if($statement->rowCount() == 0){
        echo '<game status="error" msg="no teams exist"/>';
    }
    if($isFlagPickedUp == 1){
        echo '<game status="yes" msg="' . $isFlagPickedUp . '"/>';
    } else {
        echo '<game status="no" msg="' . $isFlagPickedUp  . '"/>';
    }
    exit;
}