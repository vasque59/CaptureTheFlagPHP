<?php
require_once "db.inc.php";

if(!isset($_POST['xml'])) {
    echo '<game status="no" msg="missing XML" />';
    exit;
}

if (!isset($_GET['gameid'])) {
    echo '<game status="no" msg="missing gameid" />';
    exit;
}

process($_GET['gameid'], stripslashes($_POST['xml']));

echo '<game status="yes" />';

function process($game, $xml) {
    // Connect to the database
    $pdo = pdo_connect();

    $gameQ = $pdo->quote($game);
    $xmlQ = $pdo->quote($xml);

    $query = "UPDATE connectgame SET gamepieces=$xmlQ where id=$gameQ";

    if (!$pdo->query($query)) {
        echo '<game status="no" msg="failed insert" />';
        exit;
    }

}