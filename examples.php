<?php

include("parser.php");

$names = array( "Mr Anthony R Von Fange III",   
                "Sara Ann Fraser", 
                "Adam",
                "Jonathan Smith", 
                "Mr John Doe", 
                "Anthony Von Fange III", 
                "Smarty Pants Phd", 
                "Mark P Williams"
                );

foreach ($names as $name) {
    echo "<b>{$name}</b><br>";
    $parser = new FullNameParser();
    $split_name = $parser->split_full_name($name);
    print_r($split_name);
    foreach ($split_name as $key => $value) {
        echo "{$key}: {$value}<br>";
    }
    echo "<hr>";
}


?>