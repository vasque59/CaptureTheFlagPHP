<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['user'], $_GET['gameid']);

function process($user, $game) {
    // Connect to the database
    $pdo = pdo_connect();

    // change turns to the opposite user
    switchTurn($pdo, $user, $game);
}

function switchTurn($pdo, $user, $game) {
    $gameQ = $pdo->quote($game);
    $query = "SELECT gamestatus from connectgame where id=$gameQ";
    $userid = getUserID($pdo, $user, $game);


    $status = "<connectgame status=\"active\" turn=\"$userid\" />";
    $statusQ = $pdo->quote($status);

    $rows = $pdo->query($query);
    if($row = $rows->fetch()) {
        $query = "UPDATE connectgame SET gamestatus=$statusQ WHERE id = $gameQ";
        $pdo->query($query);
    }
}

/**
 * Ask the database for the user ID
 */
function getUserID($pdo, $user, $game) {
    // Does the user exist in the database?
    $userQ = $pdo->quote($user);
    $gameQ = $pdo->quote($game);

    $query = "SELECT id from connectuser where user=$userQ";
    $rows = $pdo->query($query);
    $row = $rows->fetch();
    $userid = $row['id'];

    $query = "SELECT id, player1, player2, gamestatus from connectgame where id=$gameQ";

    $rows = $pdo->query($query);
    if($row = $rows->fetch()) {
        $status = $row['gamestatus'];
        $turn = getTurn(stripslashes($status));

        // identify if this user can even make their turn
        // if they can, switch turns
        if ($turn == $userid) {
            // switch turns!
            if ($userid == $row['player1']) {
                // switch to 2nd player
                return $row['player2'];
            } else {
                // switch to first player
                return $row['player1'];
            }
        } else {
            // don't change turns if you aren't supposed to
            return $turn;
        }
    }
}

function getTurn($xmltext) {
    // Load the XML
    $xml = new XMLReader();
    if(!$xml->XML($xmltext)) {
        echo '<hatter status="no" msg="invalid XML" />';
        exit;
    }
    // Connect to the database
    $pdo = pdo_connect();

    // Read to the start tag
    while($xml->read()) {
        if ($xml->nodeType == XMLReader::ELEMENT && $xml->name == "connectgame") {
            // We have the connectgame tag
            $turn = $xml->getAttribute("turn");
            return $turn;
        }
    }
}