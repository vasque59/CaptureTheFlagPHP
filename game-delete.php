<?php
require_once "db.inc.php";

process();

function process() {
    // Connect to the database
    $pdo = pdo_connect();

    deleteGame($pdo);
}

function deleteGame($pdo) {

    // Delete the game
    $sql =<<<SQL
    DELETE from GameTable
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();

    // Delete the Teams
    $sql =<<<SQL
    DELETE from Team
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();

    // Delete the Users
    $sql =<<<SQL
    DELETE from ctfuser
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();


}
