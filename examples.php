<?php

require_once('parser.php');

// examples & a poor mans test suite
// intentionally shows examples that fail 

$names = array(
  "Mr Anthony R Von Fange III"  => array("salutation" => "Mr.",
                                        "fname" => "Anthony", 
                                        "initials" => "R", 
                                        "lname" => "Von Fange", 
                                        "suffix" => "III"),
  "J. B. Hunt"                  => array("salutation" => "", 
                                          "fname" => "J.", 
                                          "initials" => "B.", 
                                          "lname" => "Hunt",
                                          "suffix" => ""),
  "J.B. Hunt"                   => array("salutation" => "", 
                                          "fname" => "J.B.", 
                                          "initials" => "", 
                                          "lname" => "Hunt",
                                          "suffix" => ""),
  // fails. both initials should be capitalized
  "JB Hunt"                     => array("salutation" => "", 
                                          "fname" => "JB", 
                                          "initials" => "", 
                                          "lname" => "Hunt",
                                          "suffix" => ""),
  "Edward Senior III"           => array("salutation" => "", 
                                          "fname" => "Edward", 
                                          "initials" => "", 
                                          "lname" => "Senior",
                                          "suffix" => "III"),
  "Edward Dale Senior II"       => array("salutation" => "", 
                                          "fname" => "Edward Dale", 
                                          "initials" => "", 
                                          "lname" => "Senior",
                                          "suffix" => "II"),
  "Dale Edward Jones Senior"    => array("salutation" => "", 
                                          "fname" => "Dale Edward", 
                                          "initials" => "", 
                                          "lname" => "Jones",
                                          "suffix" => "Senior"),
  "Edward Senior II"            => array("salutation" => "", 
                                          "fname" => "Edward", 
                                          "initials" => "", 
                                          "lname" => "Senior",
                                          "suffix" => "II"),
  "Dale Edward Senior II, PhD"  => array("salutation" => "", 
                                          "fname" => "Dale Edward", 
                                          "initials" => "", 
                                          "lname" => "Senior",
                                          "suffix" => "II, PhD"),
  "Jason Rodriguez Sr."         =>  array("salutation" => "", 
                                          "fname" => "Dale Edward", 
                                          "initials" => "", 
                                          "lname" => "Sr.",
                                          "suffix" => "II, PhD"),
  "Jason Senior"                =>  array("salutation" => "", 
                                          "fname" => "Jason", 
                                          "initials" => "", 
                                          "lname" => "Senior",
                                          "suffix" => ""),
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
echo "<td>Passed?</td></tr></thead><tbody>";

foreach ($names as $name => $expected_values) {
  echo "<tr>";
  echo "<td>{$name}</td>";
  $split_name = $parser->parse_name($name, true);
  foreach ($headers as $col) {
    echo "<td>".$split_name[$col]."</td>";
  }
  $passed = ($split_name === $expected_values);
  echo ($passed) ? "<td>YES</td>" : "<td>NO</td>";
  echo "</tr>";
}
echo "</tbody></table>";

?>