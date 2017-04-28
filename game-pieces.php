<?php
require_once "db.inc.php";

// Process in a function
process($_GET['gameid']);

function process($game) {
    // Connect to the database
    $pdo = pdo_connect();

    getPieces($pdo, $game);
}

function getPieces($pdo, $game) {
    // parse the XML from the database to check if
    // turn number == this user id
    // if it is, echo <game status="yes">
    // else echo <game status="no">

    $gameQ = $pdo->quote($game);
    $query = "SELECT gamepieces from connectgame where id=$gameQ";

    $rows = $pdo->query($query);
    if($row = $rows->fetch()) {
        echo $row['gamepieces'];
    }
}