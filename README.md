PHP-Name-Parser
===============

![Build Status](https://github.com/joshfraser/PHP-Name-Parser/workflows/build/badge.svg)

PHP library to split names into their respective components.  Besides detecting first and last names, this library attempts to handle prefixes, suffixes, initials and compound last names like "Von Fange".  It also normalizes prefixes (Mister -> Mr.) and fixes capitalization (JOHN SMITH -> John Smith).

**Installation:**

    composer require joshfraser/php-name-parser

**Usage:**

    include("parser.php");

    $parser = new FullNameParser();
    $parser->parse_name("Mr Anthony R Von Fange III");

**Results:**

    Array (
        [nickname] =>
        [salutation] => Mr.
        [fname] => Anthony
        [initials] => R
        [lname] => Von Fange
        [suffix] => III
    )

**The algorithm:**

We start by splitting the full name into separate words. We then do a dictionary lookup on the first and last words to see if they are a common prefix or suffix. Next, we take the middle portion of the string (everything minus the prefix & suffix) and look at everything except the last word of that string. We then loop through each of those words concatenating them together to make up the first name. While we’re doing that, we watch for any indication of a compound last name. It turns out that almost every compound last name starts with 1 of 16 prefixes (Von, Van, Vere, etc). If we see one of those prefixes, we break out of the first name loop and move on to concatenating the last name. We handle the capitalization issue by checking for camel-case before uppercasing the first letter of each word and lowercasing everything else. I wrote special cases for periods and dashes. We also have a couple other special cases, like ignoring words in parentheses all-together.

Check examples.php for the test suite and examples of how various name formats are parsed.

**Possible improvements**

* Handle the "Lname, Fname" format
* Separate the parsing of the name from the normalization & capitalization & make those optional
* Seperate the dictionaries from the code to make it easier to do localization 
* Add common name libraries to allow for things like gender detection

**Same logic, different languages**

* [Name Parser in Java](https://github.com/gkhays/NameParser)
* [Name Parser in JavaScript](https://github.com/joshfraser/JavaScript-Name-Parser)
* [Name Parser in CSharp](https://github.com/ianlee74/CSharp-Name-Parser)

**Credits & license:**

* Read more about the inspiration for this [PHP Name Parser](http://www.onlineaspect.com/2009/08/17/splitting-names/) library by [Josh Fraser](http://joshfraser.com)
* Special thanks to [Josh Jones](https://github.com/UberNerdBoy), [Timothy Wood](https://github.com/codearachnid), [Michael Waskosky](https://github.com/waskosky), [Eric Celeste](https://github.com/efc), [Josh Houghtelin](https://github.com/jhoughtelin) and [monitaure](https://www.github.com/monitaure) for their contributions.  Pull requests are always welcome as long as you don't break the test suite.
* Released under Apache 2.0 license

