<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['user']);

/**
 * Process the query
 * @param $user the user to create if not in database
 * @param $password the password to use for the account
 */
function process($username) {
    // Connect to the database
    $pdo = pdo_connect();


    $userid = createUser($pdo, $username);
    echo "<game status=\"yes\">";
    echo "</game>";
}

/**
 * Ask the database for the user ID. If the user exists, the password
 * must match.
 * @param $pdo PHP Data Object
 * @param $username The user name
 * @return id if successful or exits if not
 */
function createUser($pdo, $username) {
    // Does the user exist in the database?
    $userQ = $pdo->quote($username);
    $query = "SELECT id from ctfuser where name=$userQ";

    $rows = $pdo->query($query);
    if (!($row = $rows->fetch())){

                $sql =<<<SQL
    SELECT LastTeam from GameTable
SQL;
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $row = $statement->fetch();
        $teamID = $row['LastTeam'] == 1 ? 2 : 1;
        $query2 = "UPDATE GameTable SET LastTeam=$teamID";
        $pdo->query($query2);
        // Insert the user into the ctfuser table
        $query3 = "INSERT INTO ctfuser(name,teamID) VALUES ($userQ,$teamID)";
        $pdo->query($query3);

        // Update the LastTeam attribute in GameTable\
        echo "<game status=\"created user\" msg='$teamID'>";
        echo "</game>";
        exit;
    }

    echo '<game status="no" msg="user error" />';
    exit;
}