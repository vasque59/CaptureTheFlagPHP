<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
scorePoint($_GET['teamid']);

function scorePointSco($teamid)
{
    // Connect to the database
    $pdo = pdo_connect();

    $pdo = pdo_connect();
    $sql =<<<SQL
    UPDATE Team SET points=points + 1 WHERE id=$teamid
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();
}

?>