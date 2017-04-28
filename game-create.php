<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['user'], $_GET['pw'], $_GET['pw2']);

/**
 * Process the query
 * @param $user the user to create if not in database
 * @param $password the password to use for the account
 */
function process($user, $password, $password2) {
    // Connect to the database
    $pdo = pdo_connect();

    if ($password != $password2) {
        echo "<game status=\"no\" msg=\"passwords don\'t match\" />";
        exit;
    }
    $userid = createUser($pdo, $user, $password);
    echo "<game status=\"yes\">";
    echo "</game>";
}

/**
 * Ask the database for the user ID. If the user exists, the password
 * must match.
 * @param $pdo PHP Data Object
 * @param $user The user name
 * @param $password Password
 * @return id if successful or exits if not
 */
function createUser($pdo, $user, $password) {
    // Does the user exist in the database?
    $userQ = $pdo->quote($user);
    $passwordQ = $pdo->quote($password);
    $query = "SELECT id, password from connectuser where user=$userQ";



    $rows = $pdo->query($query);
    if (!($row = $rows->fetch())){
        $query2 = "INSERT INTO connectuser(user, password) VALUES ($userQ,$passwordQ)";
        $pdo->query($query2);
        echo "<game status=\"yes\">";
        echo "</game>";
        exit;
    }

    echo '<game status="no" msg="user error" />';
    exit;
}