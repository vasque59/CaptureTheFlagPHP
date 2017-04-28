<?php
require_once "db.inc.php";

if (!isset($_GET['gameid'])) {
    echo '<game status="no" msg="missing gameid" />';
    exit;
}

process($_GET['gameid']);

function process($game) {
    // Connect to the database
    $pdo = pdo_connect();

    deleteGame($game, $pdo);
}

function deleteGame($game, $pdo) {
    $gameQ = $pdo->quote($game);

    $query = "DELETE from connectgame where id=$gameQ";
    $pdo->query($query);
}