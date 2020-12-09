<?php

use PHPUnit\Framework\TestCase;

class FullNameParserTest extends TestCase
{

    /** @test */
    public function testProSuffix()
    {
        $parser = new FullNameParser();

        $tests = [
            'Smarty Pants Phd' => 'Phd',
            'Smarty Pants PHD' => 'PHD',
            'OLD MACDONALD, PHD' => 'PHD',
        ];

        $tests_no_match = [
            'OLD MACDONALD',
            'OLD PHDMACDONALDPHD',
            'Prof. Ron Brown',
        ];

        foreach ($tests as $test => $expected_result) {
            $suffixes = $parser->get_pro_suffix($test);
            // $this->assertTrue(false !== array_search($expected_result, $suffixes));
            $this->assertContains($expected_result, $suffixes);
        }

        foreach ($tests_no_match as $test) {
            $suffixes = $parser->get_pro_suffix($test);
            // Should get empty array
            $this->assertSame($suffixes, []);
        }
    }

    /**
     * @dataProvider functionalNameProvider
     */
    public function testName($name, $expected_result)
    {
        $parser = new FullNameParser();
        $split_name = $parser->parse_name($name, true);
        $this->assertSame($expected_result, $split_name);
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
                    "salutation"     => "Mr.",
                    "fname"          => "Anthony",
                    "initials"       => "R",
                    "lname"          => "Von Fange",
                    "lname_base"     => "Fange",
                    "lname_compound" => "Von",
                    "suffix"         => "III"
                )
            ),
            array(
                "J. B. Hunt",
                array(
                    "salutation"     => "",
                    "fname"          => "J.",
                    "initials"       => "B.",
                    "lname"          => "Hunt",
                    "lname_base"     => "Hunt",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "J.B. Hunt",
                array(
                    "salutation"     => "",
                    "fname"          => "J.B.",
                    "initials"       => "",
                    "lname"          => "Hunt",
                    "lname_base"     => "Hunt",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Edward Senior III",
                array(
                    "salutation"     => "",
                    "fname"          => "Edward",
                    "initials"       => "",
                    "lname"          => "Senior",
                    "lname_base"     => "Senior",
                    "lname_compound" => "",
                    "suffix"         => "III"
                )
            ),
            array(
                "Edward Dale Senior II",
                array(
                    "salutation"     => "",
                    "fname"          => "Edward Dale",
                    "initials"       => "",
                    "lname"          => "Senior",
                    "lname_base"     => "Senior",
                    "lname_compound" => "",
                    "suffix"         => "II"
                )
            ),
            array(
                "Dale Edward Jones Senior",
                array(
                    "salutation"     => "",
                    "fname"          => "Dale Edward",
                    "initials"       => "",
                    "lname"          => "Jones",
                    "lname_base"     => "Jones",
                    "lname_compound" => "",
                    "suffix"         => "Senior"
                )
            ),
            array(
                "Edward Senior II",
                array(
                    "salutation"     => "",
                    "fname"          => "Edward",
                    "initials"       => "",
                    "lname"          => "Senior",
                    "lname_base"     => "Senior",
                    "lname_compound" => "",
                    "suffix"         => "II"
                )
            ),
            array(
                "Dale Edward Senior II, PhD",
                array(
                    "salutation"     => "",
                    "fname"          => "Dale Edward",
                    "initials"       => "",
                    "lname"          => "Senior",
                    "lname_base"     => "Senior",
                    "lname_compound" => "",
                    "suffix"         => "II, PhD"
                )
            ),
            array(
                "Jason Rodriguez Sr.",
                array(
                    "salutation"     => "",
                    "fname"          => "Jason",
                    "initials"       => "",
                    "lname"          => "Rodriguez",
                    "lname_base"     => "Rodriguez",
                    "lname_compound" => "",
                    "suffix"         => "Sr"
                )
            ),
            array(
                "Jason Senior",
                array(
                    "salutation"     => "",
                    "fname"          => "Jason",
                    "initials"       => "",
                    "lname"          => "Senior",
                    "lname_base"     => "Senior",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Bill Junior",
                array(
                    "salutation"     => "",
                    "fname"          => "Bill",
                    "initials"       => "",
                    "lname"          => "Junior",
                    "lname_base"     => "Junior",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Sara Ann Fraser",
                array(
                    "salutation"     => "",
                    "fname"          => "Sara Ann",
                    "initials"       => "",
                    "lname"          => "Fraser",
                    "lname_base"     => "Fraser",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Adam",
                array(
                    "salutation"     => "",
                    "fname"          => "Adam",
                    "initials"       => "",
                    "lname"          => "",
                    "lname_base"     => "",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "OLD MACDONALD",
                array(
                    "salutation"     => "",
                    "fname"          => "Old",
                    "initials"       => "",
                    "lname"          => "Macdonald",
                    "lname_base"     => "Macdonald",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Old MacDonald",
                array(
                    "salutation"     => "",
                    "fname"          => "Old",
                    "initials"       => "",
                    "lname"          => "MacDonald",
                    "lname_base"     => "MacDonald",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Old McDonald",
                array(
                    "salutation"     => "",
                    "fname"          => "Old",
                    "initials"       => "",
                    "lname"          => "McDonald",
                    "lname_base"     => "McDonald",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Old Mc Donald",
                array(
                    "salutation"     => "",
                    "fname"          => "Old MC",
                    "initials"       => "",
                    "lname"          => "Donald",
                    "lname_base"     => "Donald",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Old Mac Donald",
                array(
                    "salutation"     => "",
                    "fname"          => "Old Mac",
                    "initials"       => "",
                    "lname"          => "Donald",
                    "lname_base"     => "Donald",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "James Van Allen",
                array(
                    "salutation"     => "",
                    "fname"          => "James",
                    "initials"       => "",
                    "lname"          => "Van Allen",
                    "lname_base"     => "Allen",
                    "lname_compound" => "Van",
                    "suffix"         => ""
                )
            ),
            array(
                "Jimmy (Bubba) Smith",
                array(
                    "nickname"       => "Bubba",
                    "salutation"     => "",
                    "fname"          => "Jimmy",
                    "initials"       => "",
                    "lname"          => "Smith",
                    "lname_base"     => "Smith",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Miss Jennifer Shrader Lawrence",
                array(
                    "salutation"     => "Ms.",
                    "fname"          => "Jennifer Shrader",
                    "initials"       => "",
                    "lname"          => "Lawrence",
                    "lname_base"     => "Lawrence",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Jonathan Smith, MD",
                array(
                    "salutation"     => "",
                    "fname"          => "Jonathan",
                    "initials"       => "",
                    "lname"          => "Smith",
                    "lname_base"     => "Smith",
                    "lname_compound" => "",
                    "suffix"         => "MD"
                )
            ),
            array(
                "Dr. Jonathan Smith",
                array(
                    "salutation"     => "Dr.",
                    "fname"          => "Jonathan",
                    "initials"       => "",
                    "lname"          => "Smith",
                    "lname_base"     => "Smith",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Jonathan Smith IV, PhD",
                array(
                    "salutation"     => "",
                    "fname"          => "Jonathan",
                    "initials"       => "",
                    "lname"          => "Smith",
                    "lname_base"     => "Smith",
                    "lname_compound" => "",
                    "suffix"         => "IV, PhD"
                )
            ),
            array(
                "Miss Jamie P. Harrowitz",
                array(
                    "salutation"     => "Ms.",
                    "fname"          => "Jamie",
                    "initials"       => "P.",
                    "lname"          => "Harrowitz",
                    "lname_base"     => "Harrowitz",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Mr John Doe",
                array(
                    "salutation"     => "Mr.",
                    "fname"          => "John",
                    "initials"       => "",
                    "lname"          => "Doe",
                    "lname_base"     => "Doe",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Rev. Dr John Doe",
                array(
                    "salutation"     => "Rev. Dr.",
                    "fname"          => "John",
                    "initials"       => "",
                    "lname"          => "Doe",
                    "lname_base"     => "Doe",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Anthony Von Fange III",
                array(
                    "salutation"     => "",
                    "fname"          => "Anthony",
                    "initials"       => "",
                    "lname"          => "Von Fange",
                    "lname_base"     => "Fange",
                    "lname_compound" => "Von",
                    "suffix"         => "III"
                )
            ),
            array(
                "Anthony Von Fange III, PhD",
                array(
                    "salutation"     => "",
                    "fname"          => "Anthony",
                    "initials"       => "",
                    "lname"          => "Von Fange",
                    "lname_base"     => "Fange",
                    "lname_compound" => "Von",
                    "suffix"         => "III, PhD"
                )
            ),
            array(
                "Smarty Pants Phd",
                array(
                    "salutation"     => "",
                    "fname"          => "Smarty",
                    "initials"       => "",
                    "lname"          => "Pants",
                    "lname_base"     => "Pants",
                    "lname_compound" => "",
                    "suffix"         => "Phd"
                )
            ),
            array(
                "Mark Peter Williams",
                array(
                    "salutation"     => "",
                    "fname"          => "Mark Peter",
                    "initials"       => "",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Mark P Williams",
                array(
                    "salutation"     => "",
                    "fname"          => "Mark",
                    "initials"       => "P",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Mark P. Williams",
                array(
                    "salutation"     => "",
                    "fname"          => "Mark",
                    "initials"       => "P.",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "M Peter Williams",
                array(
                    "salutation"     => "",
                    "fname"          => "Peter",
                    "initials"       => "M",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "M. Peter Williams",
                array(
                    "salutation"     => "",
                    "fname"          => "Peter",
                    "initials"       => "M.",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "M. P. Williams",
                array(
                    "salutation"     => "",
                    "fname"          => "M.",
                    "initials"       => "P.",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "The Rev. Mark Williams",
                array(
                    "salutation"     => "Rev.",
                    "fname"          => "Mark",
                    "initials"       => "",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Mister Mark Williams",
                array(
                    "salutation"     => "Mr.",
                    "fname"          => "Mark",
                    "initials"       => "",
                    "lname"          => "Williams",
                    "lname_base"     => "Williams",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Rev Al Sharpton",
                array(
                    "salutation"     => "Rev.",
                    "fname"          => "Al",
                    "initials"       => "",
                    "lname"          => "Sharpton",
                    "lname_base"     => "Sharpton",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            array(
                "Dr Ty P. Bennington iIi",
                array(
                    "salutation"     => "Dr.",
                    "fname"          => "Ty",
                    "initials"       => "P.",
                    "lname"          => "Bennington",
                    "lname_base"     => "Bennington",
                    "lname_compound" => "",
                    "suffix"         => "III"
                )
            ),
            array(
                "Prof. Ron Brown MD",
                array(
                    "salutation"     => "Prof.",
                    "fname"          => "Ron",
                    "initials"       => "",
                    "lname"          => "Brown",
                    "lname_base"     => "Brown",
                    "lname_compound" => "",
                    "suffix"         => "MD"
                )
            ),
        );
    }

    public function disfunctionalNameProvider()
    {
        return array(
            // fails. format not yet supported
            array(
                "Fraser, Joshua",
                array(
                    "salutation"     => "",
                    "fname"          => "Joshua",
                    "initials"       => "",
                    "lname"          => "Fraser",
                    "lname_base"     => "Fraser",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            // fails. both initials should be capitalized
            array(
                "JB Hunt",
                array(
                    "salutation"     => "",
                    "fname"          => "Jb",
                    "initials"       => "",
                    "lname"          => "Hunt",
                    "lname_base"     => "Hunt",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            ),
            // fails.  should normalize the PhD suffix
            array(
                "Anthony Von Fange III, PHD",
                array(
                    "salutation"     => "",
                    "fname"          => "Anthony",
                    "initials"       => "",
                    "lname"          => "Von Fange",
                    "lname_base"     => "Von Fange",
                    "lname_compound" => "",
                    "suffix"         => "III, PhD"
                )
            ),
            // fails.  should treat "Silly" as the nickname or remove altogether
            array(
                "Not So Smarty Pants, Silly",
                array(
                    "nickname"       => "Silly",
                    "salutation"     => "",
                    "fname"          => "Not So Smarty",
                    "initials"       => "",
                    "lname"          => "Pants",
                    "lname_base"     => "Pants",
                    "lname_compound" => "",
                    "suffix"         => ""
                )
            )
        );
    }
}
