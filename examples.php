<?php

require_once('parser.php');

$names = array(
  "Mr Anthony R Von Fange III",
  "J. B. Hunt",
  "J.B. Hunt",
  "JB Hunt",
  "Edward Senior III",
  "Edward Dale Senior II",
  "Dale Edward Jones Senior",
  "Edward Senior II",
  "Dale Edward Senior II, PhD",
  "Jason Rodriguez Sr.",
  "Jason Senior",
  "Bill Junior",
  "Sara Ann Fraser",
  "Adam",
  "Old MacDonald",
  "Old McDonald",
  "Old Mc Donald",
  "Old Mac Donald",
  "James van Allen",
  "Jimmy John (Bubba) Wilkinson III",
  "Miss Jennifer Shrader Lawrence",
  "Jonathan Smith, MD",
  "Dr. Jonathan Smith",
  "Jonathan Smith IV, PhD",
  "Miss Jamie P. Harrowitz",
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
  $split_name = $parser->parse_name($name, true);
  echo '<pre>';
  print_r($split_name);
  echo '</pre>';
  echo '<hr>';
}