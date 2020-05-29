<?php

require_once('parser.php');

// examples & a poor mans test suite
// intentionally shows examples that fail

$names = [
    "Mr Anthony R Von Fange III"     => [
        "salutation" => "Mr.",
        "fname"      => "Anthony",
        "initials"   => "R",
        "lname"      => "Von Fange",
        "suffix"     => "III",
    ],
    "J. B. Hunt"                     => [
        "salutation" => "",
        "fname"      => "J.",
        "initials"   => "B.",
        "lname"      => "Hunt",
        "suffix"     => "",
    ],
    "J.B. Hunt"                      => [
        "salutation" => "",
        "fname"      => "J.B.",
        "initials"   => "",
        "lname"      => "Hunt",
        "suffix"     => "",
    ],
    "Edward Senior III"              => [
        "salutation" => "",
        "fname"      => "Edward",
        "initials"   => "",
        "lname"      => "Senior",
        "suffix"     => "III",
    ],
    "Edward Dale Senior II"          => [
        "salutation" => "",
        "fname"      => "Edward Dale",
        "initials"   => "",
        "lname"      => "Senior",
        "suffix"     => "II",
    ],
    "Dale Edward Jones Senior"       => [
        "salutation" => "",
        "fname"      => "Dale Edward",
        "initials"   => "",
        "lname"      => "Jones",
        "suffix"     => "Senior",
    ],
    "Edward Senior II"               => [
        "salutation" => "",
        "fname"      => "Edward",
        "initials"   => "",
        "lname"      => "Senior",
        "suffix"     => "II",
    ],
    "Dale Edward Senior II, PhD"     => [
        "salutation" => "",
        "fname"      => "Dale Edward",
        "initials"   => "",
        "lname"      => "Senior",
        "suffix"     => "II, PhD",
    ],
    "Jason Rodriguez Sr."            => [
        "salutation" => "",
        "fname"      => "Jason",
        "initials"   => "",
        "lname"      => "Rodriguez",
        "suffix"     => "Sr",
    ],
    "Jason Senior"                   => [
        "salutation" => "",
        "fname"      => "Jason",
        "initials"   => "",
        "lname"      => "Senior",
        "suffix"     => "",
    ],
    "Bill Junior"                    => [
        "salutation" => "",
        "fname"      => "Bill",
        "initials"   => "",
        "lname"      => "Junior",
        "suffix"     => "",
    ],
    "Sara Ann Fraser"                => [
        "salutation" => "",
        "fname"      => "Sara Ann",
        "initials"   => "",
        "lname"      => "Fraser",
        "suffix"     => "",
    ],
    "Adam"                           => [
        "salutation" => "",
        "fname"      => "Adam",
        "initials"   => "",
        "lname"      => "",
        "suffix"     => "",
    ],
    "OLD MACDONALD"                  => [
        "salutation" => "",
        "fname"      => "Old",
        "initials"   => "",
        "lname"      => "Macdonald",
        "suffix"     => "",
    ],
    "Old MacDonald"                  => [
        "salutation" => "",
        "fname"      => "Old",
        "initials"   => "",
        "lname"      => "MacDonald",
        "suffix"     => "",
    ],
    "Old McDonald"                   => [
        "salutation" => "",
        "fname"      => "Old",
        "initials"   => "",
        "lname"      => "McDonald",
        "suffix"     => "",
    ],
    "Old Mc Donald"                  => [
        "salutation" => "",
        "fname"      => "Old Mc",
        "initials"   => "",
        "lname"      => "Donald",
        "suffix"     => "",
    ],
    "Old Mac Donald"                 => [
        "salutation" => "",
        "fname"      => "Old Mac",
        "initials"   => "",
        "lname"      => "Donald",
        "suffix"     => "",
    ],
    "James van Allen"                => [
        "salutation" => "",
        "fname"      => "James",
        "initials"   => "",
        "lname"      => "Van Allen",
        "suffix"     => "",
    ],
    "Jimmy (Bubba) Smith"            => [
        "nickname"   => "Bubba",
        "salutation" => "",
        "fname"      => "Jimmy",
        "initials"   => "",
        "lname"      => "Smith",
        "suffix"     => "",
    ],
    "Miss Jennifer Shrader Lawrence" => [
        "salutation" => "Ms.",
        "fname"      => "Jennifer Shrader",
        "initials"   => "",
        "lname"      => "Lawrence",
        "suffix"     => "",
    ],
    "Jonathan Smith, MD"             => [
        "salutation" => "",
        "fname"      => "Jonathan",
        "initials"   => "",
        "lname"      => "Smith",
        "suffix"     => "MD",
    ],
    "Dr. Jonathan Smith"             => [
        "salutation" => "Dr.",
        "fname"      => "Jonathan",
        "initials"   => "",
        "lname"      => "Smith",
        "suffix"     => "",
    ],
    "Jonathan Smith IV, PhD"         => [
        "salutation" => "",
        "fname"      => "Jonathan",
        "initials"   => "",
        "lname"      => "Smith",
        "suffix"     => "IV, PhD",
    ],
    "Miss Jamie P. Harrowitz"        => [
        "salutation" => "Ms.",
        "fname"      => "Jamie",
        "initials"   => "P.",
        "lname"      => "Harrowitz",
        "suffix"     => "",
    ],
    "Mr John Doe"                    => [
        "salutation" => "Mr.",
        "fname"      => "John",
        "initials"   => "",
        "lname"      => "Doe",
        "suffix"     => "",
    ],
    "Rev. Dr John Doe"               => [
        "salutation" => "Rev. Dr.",
        "fname"      => "John",
        "initials"   => "",
        "lname"      => "Doe",
        "suffix"     => "",
    ],
    "Anthony Von Fange III"          => [
        "salutation" => "",
        "fname"      => "Anthony",
        "initials"   => "",
        "lname"      => "Von Fange",
        "suffix"     => "III",
    ],
    "Anthony Von Fange III, PhD"     => [
        "salutation" => "",
        "fname"      => "Anthony",
        "initials"   => "",
        "lname"      => "Von Fange",
        "suffix"     => "III, PhD",
    ],
    "Smarty Pants Phd"               => [
        "salutation" => "",
        "fname"      => "Smarty",
        "initials"   => "",
        "lname"      => "Pants",
        "suffix"     => "PhD",
    ],
    "Mark Peter Williams"            => [
        "salutation" => "",
        "fname"      => "Mark Peter",
        "initials"   => "",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "Mark P Williams"                => [
        "salutation" => "",
        "fname"      => "Mark",
        "initials"   => "P",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "Mark P. Williams"               => [
        "salutation" => "",
        "fname"      => "Mark",
        "initials"   => "P.",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "M Peter Williams"               => [
        "salutation" => "",
        "fname"      => "Peter",
        "initials"   => "M",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "M. Peter Williams"              => [
        "salutation" => "",
        "fname"      => "Peter",
        "initials"   => "M.",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "M. P. Williams"                 => [
        "salutation" => "",
        "fname"      => "M.",
        "initials"   => "P.",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "The Rev. Mark Williams"         => [
        "salutation" => "Rev.",
        "fname"      => "Mark",
        "initials"   => "",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    "Mister Mark Williams"           => [
        "salutation" => "Mr.",
        "fname"      => "Mark",
        "initials"   => "",
        "lname"      => "Williams",
        "suffix"     => "",
    ],
    // fails. format not yet supported
    "Fraser, Joshua"                 => [
        "salutation" => "",
        "fname"      => "Joshua",
        "initials"   => "",
        "lname"      => "Fraser",
        "suffix"     => "",
    ],
    // fails. both initials should be capitalized
    "JB Hunt"                        => [
        "salutation" => "",
        "fname"      => "JB",
        "initials"   => "",
        "lname"      => "Hunt",
        "suffix"     => "",
    ],
    // fails.  doesn't handle multiple words inside parenthesis
    "Jimmy (Bubba Junior) Smith"     => [
        "nickname"   => "Bubba Junior",
        "salutation" => "",
        "fname"      => "Jimmy",
        "initials"   => "",
        "lname"      => "Smith",
        "suffix"     => "",
    ],
    // fails.  should normalize the PhD suffix
    "Anthony Von Fange III, PHD"     => [
        "salutation" => "",
        "fname"      => "Anthony",
        "initials"   => "",
        "lname"      => "Von Fange",
        "suffix"     => "III, PhD",
    ],
    // fails.  should treat "Silly" as the nickname or remove altogether
    "Not So Smarty Pants, Silly"     => [
        "nickname"   => "Silly",
        "salutation" => "",
        "fname"      => "Not So Smarty",
        "initials"   => "",
        "lname"      => "Pants",
        "suffix"     => "",
    ],
    "Rev Al Sharpton"                => [
        "salutation" => "Rev.",
        "fname"      => "Al",
        "initials"   => "",
        "lname"      => "Sharpton",
        "suffix"     => "",
    ],
    "Dr Ty P. Bennington iIi"        => [
        "salutation" => "Dr.",
        "fname"      => "Ty",
        "initials"   => "P.",
        "lname"      => "Bennington",
        "suffix"     => "III",
    ],
];

$parser = new FullNameParser();

$headers = ["salutation", "fname", "initials", "lname", "suffix", "nickname"];

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
            <th>Expected</th>
            <th>Parsed</th>
            <th>Passed</th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($names as $name => $expected_values):
            $split_name = $parser->parse_name($name);
            $passed = empty(array_diff_assoc($expected_values, $split_name));

            ?>
            <tr class="<?= ($passed) ? 'pass' : 'fail'; ?>">
                <td><?= $name; ?></td>
                <?php foreach ($headers as $col): ?>
                    <td><?= (isset($split_name[$col])) ? $split_name[$col] : ''; ?></td>
                <?php endforeach; ?>
                <td class="raw">
                    <pre><?= json_encode($expected_values, JSON_PRETTY_PRINT); ?></pre>
                </td>
                <td class="raw">
                    <pre><?= json_encode($split_name, JSON_PRETTY_PRINT); ?></pre>
                </td>
                <td><?= ($passed) ? 'PASS' : 'FAIL'; ?></td>
            </tr>
        <?php
        endforeach;

        ?>
        </tbody>
    </table>
</div>
</body>
</html>
