<?php

use PHPUnit\Framework\TestCase;

class HelperFunctionsTest extends TestCase
{
    protected $parser;

    public function setUp(): void
    {
        $this->parser = new FullNameParser();
    }

    public function testMbUcFirst()
    {
        $this->assertEquals('Word', $this->parser->mb_ucfirst('word'));
    }

    public function testMbStrWordCount() 
    {
        $examples = [
            '' => 0,
            'word' => 1,
            'word word' => 2,
            'word word word' => 3, 
        ];

        foreach ($examples as $string => $expected) {
            $this->assertEquals($expected, $this->parser->mb_str_word_count($string));
        }
    }
}