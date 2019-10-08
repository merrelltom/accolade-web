<?php
    $price = 200.0;
    $size = 'large';
    
    $db = new SQLite3('../db/simple_postcode.db', SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare("INSERT INTO results(price, trophy_size, paid) VALUES(:price, :size, 0)");
    $statement->bindParam(':price', $price);
    $statement->bindParam(':size', $size);
    $statement->execute();
    $id = $db->lastInsertRowID();
    echo $id;


