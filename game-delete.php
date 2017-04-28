<?php
require_once "db.inc.php";

if (!isset($_GET['gameid'])) {
    echo '<game status="no" msg="missing gameid" />';
    exit;
}
if (!isset($_GET['user'])) {
    echo '<game status="no" msg="missing user" />';
    exit;
}

process($_GET['gameid'], $_GET['user']);

function process($game, $user) {
    // Connect to the database
    $pdo = pdo_connect();

    // Will return true if both players are set to 0, otherwise will clear
    // the current user from the game.
    $toDel = finishGame($game, $user, $pdo);

    if ($toDel) {
        deleteGame($game, $pdo);
    }
}

function deleteGame($game, $pdo) {
    $gameQ = $pdo->quote($game);

    $query = "DELETE from connectgame where id=$gameQ";
    $pdo->query($query);
}

function finishGame($game, $user, $pdo) {
    $gameQ = $pdo->quote($game);
    $userQ = $pdo->quote($user);
    $zero = $pdo->quote(0);

    // Determine to set either player 1 or player 0 to "0" ( this flags as not active in the game )
    $query = "SELECT * from connectgame where player1=$userQ and id=$gameQ";

    $rows = $pdo->query($query);
    if ($row = $rows->fetch()){
        // if this point, "user" is player 1, so set it as exit state (0)
        $qry = "UPDATE connectgame SET player1=$zero WHERE id=$gameQ";
    } else {
        // else, there was no query, so "user" is player 2 -> exit player 2
        $qry = "UPDATE connectgame SET player2=$zero WHERE id=$gameQ";
    }
    $pdo->query($qry);

    // Going to need to check if we need to delete this game
    $query = "SELECT * from connectgame where player1=$zero and player2=$zero and id=$gameQ";
    $rows = $pdo->query($query);
    if ($row = $rows->fetch()) {
        //both players are 0, so can remove from database
        return true;
    }
    return false;
}