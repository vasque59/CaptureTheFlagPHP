<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
process($_GET['flagLat1'],$_GET['flagLong1'],$_GET['flagLat2'],$_GET['flagLong2']);

/**
 * Process the query
 */
function process($flagLat1,$flagLong1,$flagLat2,$flagLong2) {
    // Connect to the database
    $pdo = pdo_connect();
    createGame($pdo,$flagLat1,$flagLong1,$flagLat2,$flagLong2);
    echo "<game status=\"yes\">";
    echo "</game>";
}

/**
 * Creates the Game if one doesn't exist
 * @param $pdo PHP Data Object
 * @return id if successful or exits if not
 */
function createGame($pdo,$flagLat1,$flagLong1,$flagLat2,$flagLong2) {
    // Does the user exist in the database?
    $query = "SELECT * from GameTable";

    //echo $query;

    $rows = $pdo->query($query);
    //If no Games exist, create one
    //echo $rows->fetch()["id"];
    //echo $rows->fetch()['id'];
    $rower = $rows->fetch()["Team1"];

    if ($rower != 1){
        echo 'k';
        // Team 1 ID = 1, Team 2 = 2, Default the LastTeam chosen to 2 so first user is on Team 1
        $query2 = "INSERT INTO GameTable(Team1, Team2, LastTeam) VALUES (1,2,2)";
        $pdo->query($query2);
        createTeam($pdo,$flagLat1,$flagLong1,$flagLat2,$flagLong2);
        echo "<game status=\"game created\">";
        echo "</game>";
        exit;
    }
    else if ($rower == 1){
        echo "<game status=\"game exists\">";
        echo "</game>";
        exit;
    }


    echo '<game status="no" msg="game failed to be created" />';
    exit;
}

function createTeam($pdo, $flagLat1,$flagLong1,$flagLat2,$flagLong2){
    $sql =<<<SQL
    SELECT * from Team
SQL;
        $statement = $pdo->prepare($sql);
        $statement->execute();
        // If no teams exist, create t
        if($statement->rowCount() === 0) {
            $sql =<<<SQL
    INSERT INTO Team(flagLat,flagLong,color,isFlagPickedUp,points)VALUES($flagLat1,$flagLong1,1,0,0)
SQL;
            $statement = $pdo->prepare($sql);
            $statement->execute();

        }


    $query3 = "INSERT INTO Team(flagLat,flagLong,color,isFlagPickedUp,points)VALUES($flagLat2,$flagLong2,2,0,0)";
    $pdo->query($query3);
}
