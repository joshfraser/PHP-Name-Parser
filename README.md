PHP-Name-Parser
===============

PHP library to split names into their respective components (first, last, etc)

**Usage:**

    include("parser.php");
    print_r(split_full_name("Mr Anthony R Von Fange III");

**Results:**

    Array ( 
        [salutation] => Mr. 
        [fname] => Anthony 
        [initials] => R 
        [lname] => Von Fange 
        [suffix] => III 
    )

**The algorithm:**

We start by splitting the full name into separate words. We then do a dictionary lookup on the first and last words to see if they are a common prefix or suffix. Next, we take the middle portion of the string (everything minus the prefix & suffix) and look at everything except the last word of that string. We then loop through each of those words concatenating them together to make up the first name. While we’re doing that, we watch for any indication of a compound last name. It turns out that almost every compound last name starts with 1 of 15 prefixes (Von, Van, Vere, etc). If we see one of those prefixes, we break out of the first name loop and move on to concatenating the last name. We handle the capitalization issue by checking for camel-case before uppercasing the first letter of each word and lowercasing everything else. I wrote special cases for periods and dashes. We also have a couple other special cases, like ignoring words in parentheses all-together.

**To-do**

* Refactor as a PHP class
* Separate splitting from formatting
* Remove any words in parenthesis (not just 1 word)
* Handle the "Lname, Fname" format
* Pull out the various dictionaries and make them easier to modify
* Add rule to capitalize two letter names w/ no vowels (ex. "JP" but not "Jo")
* Add common name libraries to allow for things like gender detection

**Credits & license:**

* Read more about this [PHP Name Parser](http://www.onlineaspect.com/2009/08/17/splitting-names/) by [Josh Fraser](http://joshfraser.com)
* Released under Apache 2.0 license
