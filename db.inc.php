<?php

function pdo_connect() {
    try {
        // Production server
        $dbhost="mysql:host=mysql-user.cse.msu.edu;dbname=vasque59";
        $user = "vasque59";
        $password = "your password";
        return new PDO($dbhost, $user, $password);
    } catch(PDOException $e) {
        die( "Unable to select database");
    }
}
