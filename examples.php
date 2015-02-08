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
  "Jimmy John (Bubba Junior) Wilkinson",
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
  "Fraser, Joshua"
);


$parser = new FullNameParser();

$headers = array("salutation","fname","initials","lname","suffix","nickname");

echo "<table width='100%'>";
echo "<thead style='font-weight:bold'><tr><td></td>";
foreach ($headers as $col) {
  echo "<td>".ucfirst($col)."</td>";
}
echo "</tr></thead><tbody>";

foreach ($names as $name) {
  echo "<tr>";
  echo "<td>{$name}</td>";
  $split_name = $parser->parse_name($name, true);
  foreach ($headers as $col) {
    echo "<td>".$split_name[$col]."</td>";
  }
  echo "</tr>";
}
echo "</tbody></table>";

?>