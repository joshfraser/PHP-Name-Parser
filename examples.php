<?php

include("parser.php");

$names = array( "Mr Anthony R Von Fange III",   
                "Sara Ann Fraser", 
                "Adam",
                "Jonathan Smith", 
                "Mr John Doe", 
                "Rev. Dr John Doe", 
                "Anthony Von Fange III", 
                "Anthony Von Fange III, PhD", 
                "Smarty Pants Phd", 
                "Not So Smarty Pants, Silly", 
                "Mark Peter Williams",
                "Mark P Williams",
                "Mark P. Williams",
                "M Peter Williams",
                "M. Peter Williams",
                "M. P. Williams",
                "MP Williams",
                "The Rev. Mark Williams",
              );

$parser = new FullNameParser();

foreach ($names as $name) {
    echo "<b>{$name}</b><br>";
    $split_name = $parser->split_full_name($name);
    echo "<pre>";
    print_r($split_name);
    echo "</pre>";
    echo "<hr>";
}


?>