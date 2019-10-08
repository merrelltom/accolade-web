<?php

if (isset($_POST['postcode'])) {
    
    $values = array(-15, 0, -10, -5, 0, 5, 20, 0);
    
    $postcode = filter_input(INPUT_POST, "postcode", FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
    //$postcode =  isset($_POST['postcode']) ? $_POST['postcode'] : '';


    if ( strlen($postcode) < 7  && strlen($postcode) > 0 ) {
      $postcode = substr($postcode, 0, -3) . ' ' . substr($postcode, -3);
    }

    //print_r($postcode);
    //echo("\n");

    $db = new SQLite3('../db/simple_postcode.db', SQLITE3_OPEN_READWRITE);

    $statement = $db->prepare('SELECT "SPRGRP" FROM "simple_lookup" WHERE "PCD" = ?');
    $statement->bindValue(1, strtoupper($postcode));
    $result = $statement->execute();

    //echo("Get the 1st row as an associative array:\n");
    $val = $result->fetchArray(SQLITE3_ASSOC);

    if (array_key_exists('SPRGRP', $val)) {
      echo($values[$val['SPRGRP']]);
    } else {
      echo(-99);
    }
    
    $result->finalize();
} else {
    echo(-99);
}

