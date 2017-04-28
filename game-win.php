<?php
require_once "db.inc.php";

if (!isset($_GET['gameid'])) {
    echo '<game status="no" msg="missing gameid" />';
    exit;
}
if (!isset($_GET['user'])) {
    echo '<game status="no" msg="missing userid" />';
    exit;
}

process($_GET['gameid'], $_GET['user']);

function process($game, $userid) {
    // Connect to the database
    $pdo = pdo_connect();

    $gameQ = $pdo->quote($game);
    $query = "SELECT gamestatus from connectgame where id=$gameQ";

    $status = "<connectgame status=\"won\" winner=\"$userid\" turn=\"0\"/>";
    $statusQ = $pdo->quote($status);

    $rows = $pdo->query($query);
    if($row = $rows->fetch()) {
        $query = "UPDATE connectgame SET gamestatus=$statusQ WHERE id = $gameQ";
        $pdo->query($query);
    }

}