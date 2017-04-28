<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['user'], $_GET['pw']);

/**
 * Process the query
 * @param $user the user to look for
 * @param $password the user password
 */
function process($user, $password) {
    // Connect to the database
    $pdo = pdo_connect();

    // LOGIN, if first user, create a game. else JOIN the game
    $userid = getUser($pdo, $user, $password);

    //There should only ever be one game in the database
    //based on the design outline in project 2
    //This doesn't actually catch errors if there are 2+
    //might want to extend this in the future to not
    //explode if we have more than 1 ongoing game at once
    $gameid = getGame($pdo);

    //that function returns null if there are no active games
    if ($gameid === null) {
        // so that means the current user logging in is the
        // first user, so create a game
        $gameid = createGame($pdo, $userid);
    } else {
        // else we join the game that is found as the 2nd player logging in
        joinGame($pdo, $gameid, $userid);
    }

    // status is a mediumtext in the database named "gamestatus"
    // is basically just XML dump
    $status = getStatus($pdo, $gameid);

    // WIll need to handle deleting a game after it's over
    // in order to allow for a game the next time a player wants
    echo "<game status=\"yes\" user=\"$userid\" id=\"$gameid\">$status</game>";
}

/**
 * Ask the database for the user ID. If the user exists, the password
 * must match.
 * @param $pdo PHP Data Object
 * @param $user The user name
 * @param $password Password
 * @return id if successful or exits if not
 */
function getUser($pdo, $user, $password) {
    // Does the user exist in the database?
    $userQ = $pdo->quote($user);
    $query = "SELECT id, password from connectuser where user=$userQ";

    $rows = $pdo->query($query);
    if($row = $rows->fetch()) {
        // We found the record in the database
        // Check the password
        if($row['password'] != $password) {
            echo '<game status="no" msg="password error" />';
            exit;
        }

        return $row['id'];
    }

    echo '<game status="no" msg="user error" />';
    exit;
}

function createGame($pdo, $user) {
    $status = "<connectgame status=\"waiting\" />";
    $userQ = $pdo->quote($user);
    $statusQ = $pdo->quote($status);
    $query = "INSERT into connectgame(player1, gamestatus) values($userQ, $statusQ)";

    $pdo->query($query);

    return $pdo->lastInsertId();
}

function getGame($pdo) {
    $query = "SELECT id from connectgame";

    $rows = $pdo->query($query);
    if ($row = $rows->fetch()) {
        return $row['id'];
    } else {
        return null;
    }

}

function joinGame($pdo, $gameid, $userid) {
    $userQ = $pdo->quote($userid);
    $gameQ = $pdo->quote($gameid);

    $query = "SELECT id from connectgame WHERE id = $gameQ and player1 = $userQ";

    $pdo->query($query);

    $rows = $pdo->query($query);
    if (!($row = $rows->fetch())){
        // does not exist , so we can join the active game.
        // do an SQL update statement here to change player2 to $userQ
        // and exit
        $query = "SELECT player1 from connectgame WHERE id=$gameQ";
        $pdo->query($query);
        $rows = $pdo->query($query);
        $row = $rows->fetch();
        $playerid = $row['player1'];

        // set the turn to player 1
        $status = "<connectgame status=\"active\" turn=\"$playerid\" />";
        $statusQ = $pdo->quote($status);

        $query = "UPDATE connectgame SET player2=$userQ, gamestatus=$statusQ WHERE id = $gameQ";
        $pdo->query($query);
    } else {
        // you can't join your own game
    }
}

function getStatus($pdo, $gameid) {
    $gameQ = $pdo->quote($gameid);

    $query = "SELECT gamestatus FROM connectgame WHERE id = $gameQ";
    $rows = $pdo->query($query);
    $row = $rows->fetch();

    return $row['gamestatus'];
}