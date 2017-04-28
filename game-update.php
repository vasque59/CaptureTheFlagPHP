<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['gameid']);

function process($game) {
    // Connect to the database
    $pdo = pdo_connect();

    getStatus($pdo, $game);
}

function getStatus($pdo, $game) {
    // parse the XML from the database to check if
    // turn number == this user id
    // if it is, echo <game status="yes">
    // else echo <game status="no">

    $gameQ = $pdo->quote($game);
    $query = "SELECT gamestatus from connectgame where id=$gameQ";

    $rows = $pdo->query($query);
    if($row = $rows->fetch()) {
        echo $row['gamestatus'];
    } else {
        echo '<connectgame status="invalid" turn="0"/>';
    }
}