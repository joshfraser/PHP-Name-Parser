<?php

class FullNameParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider functionalNameProvider
     */
    public function testName($name, $expected_result)
    {
        $parser = new FullNameParser();
        $split_name = $parser->parse_name($name, true);
        $this->assertSame($split_name, $expected_result);
    }

    /**
     * @dataProvider disfunctionalNameProvider
     */
    public function testBadNames($name, $expected_result)
    {
        $parser = new FullNameParser();
        $split_name = $parser->parse_name($name, true);
        $array_equal = ($split_name === $expected_result);
        // These tests pass because the expected results do NOT match the actual results.
        $this->assertFalse($array_equal);
    }

    public function functionalNameProvider()
    {
        return array(
            array(
                "Mr Anthony R Von Fange III",
                array(
                    "salutation" => "Mr.",
                    "fname"      => "Anthony",
                    "initials"   => "R",
                    "lname"      => "Von Fange",
                    "suffix"     => "III"
                )
            ),
            array(
                "J. B. Hunt",
                array(
                    "salutation" => "",
                    "fname"      => "J.",
                    "initials"   => "B.",
                    "lname"      => "Hunt",
                    "suffix"     => ""
                )
            ),
            array(
                "J.B. Hunt",
                array(
                    "salutation" => "",
                    "fname"      => "J.B.",
                    "initials"   => "",
                    "lname"      => "Hunt",
                    "suffix"     => ""
                )
            ),
            array(
                "Edward Senior III",
                array(
                    "salutation" => "",
                    "fname"      => "Edward",
                    "initials"   => "",
                    "lname"      => "Senior",
                    "suffix"     => "III"
                )
            ),
            array(
                "Edward Dale Senior II",
                array(
                    "salutation" => "",
                    "fname"      => "Edward Dale",
                    "initials"   => "",
                    "lname"      => "Senior",
                    "suffix"     => "II"
                )
            ),
            array(
                "Dale Edward Jones Senior",
                array(
                    "salutation" => "",
                    "fname"      => "Dale Edward",
                    "initials"   => "",
                    "lname"      => "Jones",
                    "suffix"     => "Senior"
                )
            ),
            array(
                "Edward Senior II",
                array(
                    "salutation" => "",
                    "fname"      => "Edward",
                    "initials"   => "",
                    "lname"      => "Senior",
                    "suffix"     => "II"
                )
            ),
            array(
                "Dale Edward Senior II, PhD",
                array(
                    "salutation" => "",
                    "fname"      => "Dale Edward",
                    "initials"   => "",
                    "lname"      => "Senior",
                    "suffix"     => "II, PhD"
                )
            ),
            array(
                "Jason Rodriguez Sr.",
                array(
                    "salutation" => "",
                    "fname"      => "Jason",
                    "initials"   => "",
                    "lname"      => "Rodriguez",
                    "suffix"     => "Sr"
                )
            ),
            array(
                "Jason Senior",
                array(
                    "salutation" => "",
                    "fname"      => "Jason",
                    "initials"   => "",
                    "lname"      => "Senior",
                    "suffix"     => ""
                )
            ),
            array(
                "Bill Junior",
                array(
                    "salutation" => "",
                    "fname"      => "Bill",
                    "initials"   => "",
                    "lname"      => "Junior",
                    "suffix"     => ""
                )
            ),
            array(
                "Sara Ann Fraser",
                array(
                    "salutation" => "",
                    "fname"      => "Sara Ann",
                    "initials"   => "",
                    "lname"      => "Fraser",
                    "suffix"     => ""
                )
            ),
            array(
                "Adam",
                array(
                    "salutation" => "",
                    "fname"      => "Adam",
                    "initials"   => "",
                    "lname"      => "",
                    "suffix"     => ""
                )
            ),
            array(
                "OLD MACDONALD",
                array(
                    "salutation" => "",
                    "fname"      => "Old",
                    "initials"   => "",
                    "lname"      => "Macdonald",
                    "suffix"     => ""
                )
            ),
            array(
                "Old MacDonald",
                array(
                    "salutation" => "",
                    "fname"      => "Old",
                    "initials"   => "",
                    "lname"      => "MacDonald",
                    "suffix"     => ""
                )
            ),
            array(
                "Old McDonald",
                array(
                    "salutation" => "",
                    "fname"      => "Old",
                    "initials"   => "",
                    "lname"      => "McDonald",
                    "suffix"     => ""
                )
            ),
            array(
                "Old Mc Donald",
                array(
                    "salutation" => "",
                    "fname"      => "Old Mc",
                    "initials"   => "",
                    "lname"      => "Donald",
                    "suffix"     => ""
                )
            ),
            array(
                "Old Mac Donald",
                array(
                    "salutation" => "",
                    "fname"      => "Old Mac",
                    "initials"   => "",
                    "lname"      => "Donald",
                    "suffix"     => ""
                )
            ),
            array(
                "James van Allen",
                array(
                    "salutation" => "",
                    "fname"      => "James",
                    "initials"   => "",
                    "lname"      => "Van Allen",
                    "suffix"     => ""
                )
            ),
            array(
                "Jimmy (Bubba) Smith",
                array(
                    "nickname"   => "Bubba",
                    "salutation" => "",
                    "fname"      => "Jimmy",
                    "initials"   => "",
                    "lname"      => "Smith",
                    "suffix"     => ""
                )
            ),
            array(
                "Miss Jennifer Shrader Lawrence",
                array(
                    "salutation" => "Ms.",
                    "fname"      => "Jennifer Shrader",
                    "initials"   => "",
                    "lname"      => "Lawrence",
                    "suffix"     => ""
                )
            ),
            array(
                "Jonathan Smith, MD",
                array(
                    "salutation" => "",
                    "fname"      => "Jonathan",
                    "initials"   => "",
                    "lname"      => "Smith",
                    "suffix"     => "MD"
                )
            ),
            array(
                "Dr. Jonathan Smith",
                array(
                    "salutation" => "Dr.",
                    "fname"      => "Jonathan",
                    "initials"   => "",
                    "lname"      => "Smith",
                    "suffix"     => ""
                )
            ),
            array(
                "Jonathan Smith IV, PhD",
                array(
                    "salutation" => "",
                    "fname"      => "Jonathan",
                    "initials"   => "",
                    "lname"      => "Smith",
                    "suffix"     => "IV, PhD"
                )
            ),
            array(
                "Miss Jamie P. Harrowitz",
                array(
                    "salutation" => "Ms.",
                    "fname"      => "Jamie",
                    "initials"   => "P.",
                    "lname"      => "Harrowitz",
                    "suffix"     => ""
                )
            ),
            array(
                "Mr John Doe",
                array(
                    "salutation" => "Mr.",
                    "fname"      => "John",
                    "initials"   => "",
                    "lname"      => "Doe",
                    "suffix"     => ""
                )
            ),
            array(
                "Rev. Dr John Doe",
                array(
                    "salutation" => "Rev. Dr.",
                    "fname"      => "John",
                    "initials"   => "",
                    "lname"      => "Doe",
                    "suffix"     => ""
                )
            ),
            array(
                "Anthony Von Fange III",
                array(
                    "salutation" => "",
                    "fname"      => "Anthony",
                    "initials"   => "",
                    "lname"      => "Von Fange",
                    "suffix"     => "III"
                )
            ),
            array(
                "Anthony Von Fange III, PhD",
                array(
                    "salutation" => "",
                    "fname"      => "Anthony",
                    "initials"   => "",
                    "lname"      => "Von Fange",
                    "suffix"     => "III, PhD"
                )
            ),
            array(
                "Smarty Pants Phd",
                array(
                    "salutation" => "",
                    "fname"      => "Smarty",
                    "initials"   => "",
                    "lname"      => "Pants",
                    "suffix"     => "PhD"
                )
            ),
            array(
                "Mark Peter Williams",
                array(
                    "salutation" => "",
                    "fname"      => "Mark Peter",
                    "initials"   => "",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "Mark P Williams",
                array(
                    "salutation" => "",
                    "fname"      => "Mark",
                    "initials"   => "P",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "Mark P. Williams",
                array(
                    "salutation" => "",
                    "fname"      => "Mark",
                    "initials"   => "P.",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "M Peter Williams",
                array(
                    "salutation" => "",
                    "fname"      => "Peter",
                    "initials"   => "M",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "M. Peter Williams",
                array(
                    "salutation" => "",
                    "fname"      => "Peter",
                    "initials"   => "M.",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "M. P. Williams",
                array(
                    "salutation" => "",
                    "fname"      => "M.",
                    "initials"   => "P.",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "The Rev. Mark Williams",
                array(
                    "salutation" => "Rev.",
                    "fname"      => "Mark",
                    "initials"   => "",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            ),
            array(
                "Mister Mark Williams",
                array(
                    "salutation" => "Mr.",
                    "fname"      => "Mark",
                    "initials"   => "",
                    "lname"      => "Williams",
                    "suffix"     => ""
                )
            )
        );
    }

    public function disfunctionalNameProvider()
    {
        return array(
            // fails. format not yet supported
            array(
                "Fraser, Joshua",
                array(
                    "salutation" => "",
                    "fname"      => "Joshua",
                    "initials"   => "",
                    "lname"      => "Fraser",
                    "suffix"     => ""
                )
            ),
            // fails. both initials should be capitalized
            array(
                "JB Hunt",
                array(
                    "salutation" => "",
                    "fname"      => "JB",
                    "initials"   => "",
                    "lname"      => "Hunt",
                    "suffix"     => ""
                )
            ),
            // fails.  doesn't handle multiple words inside parenthesis
            array(
                "Jimmy (Bubba Junior) Smith",
                array(
                    "nickname"   => "Bubba Junior",
                    "salutation" => "",
                    "fname"      => "Jimmy",
                    "initials"   => "",
                    "lname"      => "Smith",
                    "suffix"     => ""
                )
            ),
            // fails.  should normalize the PhD suffix
            array(
                "Anthony Von Fange III, PHD",
                array(
                    "salutation" => "",
                    "fname"      => "Anthony",
                    "initials"   => "",
                    "lname"      => "Von Fange",
                    "suffix"     => "III, PhD"
                )
            ),
            // fails.  should treat "Silly" as the nickname or remove altogether
            array(
                "Not So Smarty Pants, Silly",
                array(
                    "nickname"   => "Silly",
                    "salutation" => "",
                    "fname"      => "Not So Smarty",
                    "initials"   => "",
                    "lname"      => "Pants",
                    "suffix"     => ""
                )
            )
        );
    }
}
