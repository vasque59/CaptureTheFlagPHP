<?php
require_once "db.inc.php";

process($_GET['teamid']);

function process($teamid) {
    // Connect to the database
    $pdo = pdo_connect();

    $sql =<<<SQL
    SELECT points from Team where color=$teamid
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $points = $statement->fetch()['points'];
    echo $points;
    echo '<game status="yes" msg="' . $points . '"/>';
    exit;


}