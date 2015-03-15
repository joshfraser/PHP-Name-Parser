<?php

require_once('parser.php');

// examples & a poor mans test suite
// intentionally shows examples that fail

$names = array(
  "Mr Anthony R Von Fange III"      => array("salutation" => "Mr.",
                                            "fname" => "Anthony",
                                            "initials" => "R",
                                            "lname" => "Von Fange",
                                            "suffix" => "III"),
  "J. B. Hunt"                      => array("salutation" => "",
                                              "fname" => "J.",
                                              "initials" => "B.",
                                              "lname" => "Hunt",
                                              "suffix" => ""),
  "J.B. Hunt"                       => array("salutation" => "",
                                              "fname" => "J.B.",
                                              "initials" => "",
                                              "lname" => "Hunt",
                                              "suffix" => ""),
  "Edward Senior III"               => array("salutation" => "",
                                              "fname" => "Edward",
                                              "initials" => "",
                                              "lname" => "Senior",
                                              "suffix" => "III"),
  "Edward Dale Senior II"           => array("salutation" => "",
                                              "fname" => "Edward Dale",
                                              "initials" => "",
                                              "lname" => "Senior",
                                              "suffix" => "II"),
  "Dale Edward Jones Senior"        => array("salutation" => "",
                                              "fname" => "Dale Edward",
                                              "initials" => "",
                                              "lname" => "Jones",
                                              "suffix" => "Senior"),
  "Edward Senior II"                => array("salutation" => "",
                                              "fname" => "Edward",
                                              "initials" => "",
                                              "lname" => "Senior",
                                              "suffix" => "II"),
  "Dale Edward Senior II, PhD"      => array("salutation" => "",
                                              "fname" => "Dale Edward",
                                              "initials" => "",
                                              "lname" => "Senior",
                                              "suffix" => "II, PhD"),
  "Jason Rodriguez Sr."             =>  array("salutation" => "",
                                              "fname" => "Jason",
                                              "initials" => "",
                                              "lname" => "Rodriguez",
                                              "suffix" => "Sr"),
  "Jason Senior"                    =>  array("salutation" => "",
                                              "fname" => "Jason",
                                              "initials" => "",
                                              "lname" => "Senior",
                                              "suffix" => ""),
  "Bill Junior"                     =>  array("salutation" => "",
                                              "fname" => "Bill",
                                              "initials" => "",
                                              "lname" => "Junior",
                                              "suffix" => ""),
  "Sara Ann Fraser"                 =>  array("salutation" => "",
                                              "fname" => "Sara Ann",
                                              "initials" => "",
                                              "lname" => "Fraser",
                                              "suffix" => ""),
  "Adam"                            =>  array("salutation" => "",
                                              "fname" => "Adam",
                                              "initials" => "",
                                              "lname" => "",
                                              "suffix" => ""),
  "OLD MACDONALD"                   =>  array("salutation" => "",
                                              "fname" => "Old",
                                              "initials" => "",
                                              "lname" => "Macdonald",
                                              "suffix" => ""),
  "Old MacDonald"                   =>  array("salutation" => "",
                                              "fname" => "Old",
                                              "initials" => "",
                                              "lname" => "MacDonald",
                                              "suffix" => ""),
  "Old McDonald"                    =>  array("salutation" => "",
                                              "fname" => "Old",
                                              "initials" => "",
                                              "lname" => "McDonald",
                                              "suffix" => ""),
  "Old Mc Donald"                   =>  array("salutation" => "",
                                              "fname" => "Old Mc",
                                              "initials" => "",
                                              "lname" => "Donald",
                                              "suffix" => ""),
  "Old Mac Donald"                  =>  array("salutation" => "",
                                              "fname" => "Old Mac",
                                              "initials" => "",
                                              "lname" => "Donald",
                                              "suffix" => ""),
  "James van Allen"                 =>  array("salutation" => "",
                                              "fname" => "James",
                                              "initials" => "",
                                              "lname" => "Van Allen",
                                              "suffix" => ""),
  "Jimmy (Bubba) Smith"             =>  array("nickname" => "Bubba",
                                              "salutation" => "",
                                              "fname" => "Jimmy",
                                              "initials" => "",
                                              "lname" => "Smith",
                                              "suffix" => ""),
  "Miss Jennifer Shrader Lawrence"  =>  array("salutation" => "Ms.",
                                              "fname" => "Jennifer Shrader",
                                              "initials" => "",
                                              "lname" => "Lawrence",
                                              "suffix" => ""),
  "Jonathan Smith, MD"              =>  array("salutation" => "",
                                              "fname" => "Jonathan",
                                              "initials" => "",
                                              "lname" => "Smith",
                                              "suffix" => "MD"),
  "Dr. Jonathan Smith"              =>  array("salutation" => "Dr.",
                                              "fname" => "Jonathan",
                                              "initials" => "",
                                              "lname" => "Smith",
                                              "suffix" => ""),
  "Jonathan Smith IV, PhD"          =>  array("salutation" => "",
                                              "fname" => "Jonathan",
                                              "initials" => "",
                                              "lname" => "Smith",
                                              "suffix" => "IV, PhD"),
  "Miss Jamie P. Harrowitz"         =>  array("salutation" => "Ms.",
                                              "fname" => "Jamie",
                                              "initials" => "P.",
                                              "lname" => "Harrowitz",
                                              "suffix" => ""),
  "Mr John Doe"                     =>  array("salutation" => "Mr.",
                                              "fname" => "John",
                                              "initials" => "",
                                              "lname" => "Doe",
                                              "suffix" => ""),
  "Rev. Dr John Doe"                =>  array("salutation" => "Rev. Dr.",
                                              "fname" => "John",
                                              "initials" => "",
                                              "lname" => "Doe",
                                              "suffix" => ""),
  "Anthony Von Fange III"           =>  array("salutation" => "",
                                              "fname" => "Anthony",
                                              "initials" => "",
                                              "lname" => "Von Fange",
                                              "suffix" => "III"),
  "Anthony Von Fange III, PhD"      =>  array("salutation" => "",
                                              "fname" => "Anthony",
                                              "initials" => "",
                                              "lname" => "Von Fange",
                                              "suffix" => "III, PhD"),
  "Smarty Pants Phd"                =>  array("salutation" => "",
                                              "fname" => "Smarty",
                                              "initials" => "",
                                              "lname" => "Pants",
                                              "suffix" => "PhD"),
  "Mark Peter Williams"             =>  array("salutation" => "",
                                              "fname" => "Mark Peter",
                                              "initials" => "",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "Mark P Williams"                 =>  array("salutation" => "",
                                              "fname" => "Mark",
                                              "initials" => "P",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "Mark P. Williams"                =>  array("salutation" => "",
                                              "fname" => "Mark",
                                              "initials" => "P.",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "M Peter Williams"                =>  array("salutation" => "",
                                              "fname" => "Peter",
                                              "initials" => "M",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "M. Peter Williams"               =>  array("salutation" => "",
                                              "fname" => "Peter",
                                              "initials" => "M.",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "M. P. Williams"                  =>  array("salutation" => "",
                                              "fname" => "M.",
                                              "initials" => "P.",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "The Rev. Mark Williams"          =>  array("salutation" => "Rev.",
                                              "fname" => "Mark",
                                              "initials" => "",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  "Mister Mark Williams"          =>  array("salutation" => "Mr.",
                                              "fname" => "Mark",
                                              "initials" => "",
                                              "lname" => "Williams",
                                              "suffix" => ""),
  // fails. format not yet supported
  "Fraser, Joshua"                  =>  array("salutation" => "",
                                              "fname" => "Joshua",
                                              "initials" => "",
                                              "lname" => "Fraser",
                                              "suffix" => ""),
  // fails. both initials should be capitalized
  "JB Hunt"                         => array("salutation" => "",
                                              "fname" => "JB",
                                              "initials" => "",
                                              "lname" => "Hunt",
                                              "suffix" => ""),
  // fails.  doesn't handle multiple words inside parenthesis
  "Jimmy (Bubba Junior) Smith"      =>  array("nickname" => "Bubba Junior",
                                              "salutation" => "",
                                              "fname" => "Jimmy",
                                              "initials" => "",
                                              "lname" => "Smith",
                                              "suffix" => ""),
  // fails.  should normalize the PhD suffix
  "Anthony Von Fange III, PHD"      =>  array("salutation" => "",
                                              "fname" => "Anthony",
                                              "initials" => "",
                                              "lname" => "Von Fange",
                                              "suffix" => "III, PhD"),
  // fails.  should treat "Silly" as the nickname or remove altogether
  "Not So Smarty Pants, Silly"      =>  array("nickname" => "Silly",
                                              "salutation" => "",
                                              "fname" => "Not So Smarty",
                                              "initials" => "",
                                              "lname" => "Pants",
                                              "suffix" => ""),
);


$parser = new FullNameParser();

$headers = array("salutation","fname","initials","lname","suffix","nickname");
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Name Parser | Unit Tests</title>
    <link rel="stylesheet" href="./tests/style.css" type="text/css" media="all">
  </head>
  <body>
    <div class="wrapper">
      <table class="unit-tests">
        <thead>
          <tr>
            <th>Full Name</th>
<?php foreach ($headers as $col): ?>
            <th><?= ucfirst($col); ?></th>
<?php endforeach; ?>
            <th>Passed</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($names as $name => $expected_values): $split_name = $parser->parse_name($name); $passed = ($split_name === $expected_values); ?>
          <tr class="<?= ($passed) ? 'pass' : 'fail'; ?>">
            <td><?= $name; ?></td>
<?php foreach ($headers as $col): ?>
            <td><?= (isset($split_name[$col])) ? $split_name[$col] : ''; ?></td>
<?php endforeach; ?>
            <td><?= ($passed) ? 'PASS' : 'FAIL'; ?></td>
          </tr>
<?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </body>
</html>