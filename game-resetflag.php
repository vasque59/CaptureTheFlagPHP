<?php
require_once "db.inc.php";
echo '<?xml version="1.0" encoding="1.0" ?>';

// Process in a function
resetFlag($_GET['flagLat'],$_GET['flagLong'],$_GET['resetFlagLat'],$_GET['resetFlagLong'],$_GET['teamid']);

function resetFlag($flagLat, $flagLong,$resetFlagLat, $resetFlagLong, $teamid) {
//    echo "<p>$flagLat</p><p>$flagLong</p><p>$teamid</p>";

    $pdo = pdo_connect();

    // Grab the other teams id
    $otherTeamId = $teamid == 1 ? 2 : 1;
    // Grab the other teams flag location
    $sql =<<<SQL
    SELECT flagLat, flagLong from Team where id=$otherTeamId
SQL;
    $statement = $pdo->prepare($sql);
    $statement->execute();

    $row = $otherflagLat = $statement->fetch();
    $otherflagLat = $row['flagLat'];
    $otherflagLong = $row['flagLong'];


    $dist = distance($flagLat, $flagLong, $otherflagLat, $otherflagLong, "K") / 1000 ;
    echo "dist: $dist";
    // If we're less than 5 meters from the other teams flag, reset their flag
    if($dist < 10){
        $sql =<<<SQL
    UPDATE Team SET flagLat=$flagLat, flagLong=$flagLong, isFlagPickedUp=0 WHERE id=$otherTeamId
SQL;
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}