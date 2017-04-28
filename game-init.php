<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['gameid']);

function process($game)
{
    // Connect to the database
    $pdo = pdo_connect();

    $gameQ = $pdo->quote($game);

    $query = "select player1, player2, a.user as p1, b.user as p2 from connectgame, connectuser a, connectuser b where connectgame.id=$gameQ and player1=a.id and player2=b.id";

    $rows = $pdo->query($query);
    if ($row = $rows->fetch()) {
        $player1id = $row['player1'];
        $player2id = $row['player2'];
        $player1name = $row['p1'];
        $player2name = $row['p2'];
        $str = '<game status="yes" id="' . $game . '" user1="' . $player1id . '" player1="' . $player1name;
        $str .= '" user2="'. $player2id . '" player2="' . $player2name . '" />';
        echo $str;
    }
}

?>