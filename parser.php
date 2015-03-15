<?php



/**
 * Split a full name into its constituent parts
 *   - prefix/salutation (Mr. Mrs. Dr. etc)
 *   - given/first name
 *   - middle name/initial(s)
 *   - surname (last name)
 *   - suffix (II, PhD, Jr. etc)
*/
class FullNameParser {



  /**
   * Create the dictionary of terms for use later
   *
   *  - Common honorific prefixes (english)
   *  - Common compound surname identifiers
   *  - Common suffixes (lineage and professional)
   */
  protected $dict = array(
    'prefix' => array(
      'Mr.' => array('mr', 'mister', 'master'),
      'Mrs.' => array('mrs', 'missus', 'missis'),
      'Ms.' => array('ms', 'miss'),
      'Dr.' => array('dr'),
      'Rev.' => array("rev", "rev'd", "reverend"),
      'Fr.' => array('fr', 'father'),
      'Sr.' => array('sr', 'sister'),
      'Prof.' => array('prof', 'professor'),
      'Sir' => array('sir'),
      ' ' => array('the')
    ),
    'compound' => array('da','de','del','della','der','di','du','la','pietro','st.','st','ter','van','vanden','vere','von'),
    'suffixes' => array(
      'line' => array('I','II','III','IV','V','1st','2nd','3rd','4th','5th','Senior','Junior','Jr','Sr'),
      'prof' => array('PhD','APR','RPh','PE','MD','MA','DMD','CME')
    ),
    'vowels' => array('a','e','i','o','u')
  );



  /**
   * This is the primary method which calls all other methods
   *
   * @param string $name the full name you wish to parse
   * @return array returns associative array of name parts
   */
  public function parse_name($full_name) {

    # Remove leading/trailing whitespace
    $full_name = trim($full_name);

    # Setup default vars
    extract(array('salutation' => '', 'fname' => '', 'initials' => '', 'lname' => '', 'suffix' => ''));

    # If name contains professional suffix, assign and remove it
    $professional_suffix = $this->get_pro_suffix($full_name);
    if ($professional_suffix) {
      # Remove the suffix from full name
      $full_name = str_replace($professional_suffix, '', $full_name);
      # Remove the preceeding comma and space(s) from suffix
      $professional_suffix = preg_replace("/, */", '', $professional_suffix);
      # Normalize the case of suffix if found in dictionary
      foreach ($this->dict['suffixes']['prof'] as $prosuffix) {
        if (strtolower($prosuffix) === strtolower($professional_suffix)) {
          $professional_suffix = $prosuffix;
        }
      }
    }

    # Deal with nickname, push to array
    $has_nick = $this->get_nickname($full_name);
    if ($has_nick) {
      # Remove wrapper chars from around nickname
      $name['nickname'] = substr($has_nick, 1, (strlen($has_nick) - 2));
      # Remove the nickname from the full name
      $full_name = str_replace($has_nick, '', $full_name);
      # Get rid of consecutive spaces left by the removal
      $full_name = str_replace('  ', ' ', $full_name);
    }

    # Grab a list of words from name
    $unfiltered_name_parts = $this->break_words($full_name);

    # Is first word a title or multiple titles consecutively?
    while ($s = $this->is_salutation($unfiltered_name_parts[0])) {
      $salutation .= "$s ";
      array_shift($unfiltered_name_parts);
    }
    $salutation = trim($salutation);

    # Is last word a suffix or multiple suffixes consecutively?
    while ($s = $this->is_suffix($unfiltered_name_parts[count($unfiltered_name_parts)-1], $full_name)) {
      $suffix .= "$s ";
      array_pop($unfiltered_name_parts);
    }
    $suffix = trim($suffix);

    # If suffix and professional suffix not empty, add comma
    if (!empty($professional_suffix) && !empty($suffix)) {
      $suffix .= ', ';
    }

    # Concat professional suffix to suffix
    $suffix .= $professional_suffix;

    # set the ending range after prefix/suffix trim
    $end = count($unfiltered_name_parts);

    # concat the first name
    for ($i=0; $i<$end-1; $i++) {
      $word = $unfiltered_name_parts[$i];
      # move on to parsing the last name if we find an indicator of a compound last name (Von, Van, etc)
      # we use $i != 0 to allow for rare cases where an indicator is actually the first name (like "Von Fabella")
      if ($this->is_compound($word) && $i != 0) {
        break;
      }
      # is it a middle initial or part of their first name?
      # if we start off with an initial, we'll call it the first name
      if ($this->is_initial($word)) {
        # is the initial the first word?
        if ($i == 0) {
          # if so, do a look-ahead to see if they go by their middle name
          # for ex: "R. Jason Smith" => "Jason Smith" & "R." is stored as an initial
          # but "R. J. Smith" => "R. Smith" and "J." is stored as an initial
          if ($this->is_initial($unfiltered_name_parts[$i+1])) {
            $fname .= " ".strtoupper($word);
          }
          else {
            $initials .= " ".strtoupper($word);
          }
        }
        # otherwise, just go ahead and save the initial
        else {
          $initials .= " ".strtoupper($word);
        }
      }
      else {
        $fname .= " ".$this->fix_case($word);
      }
    }

    # check that we have more than 1 word in our string
    if ($end-0 > 1) {
      # concat the last name
      for ($i; $i < $end; $i++) {
        $lname .= " ".$this->fix_case($unfiltered_name_parts[$i]);
      }
    }
    else {
      # otherwise, single word strings are assumed to be first names
      $fname = $this->fix_case($unfiltered_name_parts[$i]);
    }

    # return the various parts in an array
    $name['salutation'] = $salutation;
    $name['fname'] = trim($fname);
    $name['initials'] = trim($initials);
    $name['lname'] = trim($lname);
    $name['suffix'] = $suffix;
    return $name;
  }



  /**
   * Breaks name into individual words
   *
   * @param string $name the full name you wish to parse
   * @return array full list of words broken down by spaces
   */
  public function break_words($name) {
    return explode(' ', $name);
  }



  /**
   * Checks for the existence of, and returns professional suffix
   *
   * @param string $name the name you wish to test
   * @return mixed returns the suffix if exists, false otherwise
   */
  protected function get_pro_suffix($name) {
    foreach ($this->dict['suffixes']['prof'] as $suffix) {
      if (preg_match("/,[\s]*$suffix\b/i", $name, $matches)) {
        return $matches[0];
      }
    }
    return false;
  }



  /**
   * Function to check name for existence of nickname based on these stipulations
   *  - String wrapped in parentheses (string)
   *  - String wrapped in double quotes "string"
   *  x String wrapped in single quotes 'string'
   *
   *  I removed the check for strings in single quotes 'string' due to possible
   *  conflicts with names that may include apostrophes. Arabic transliterations, for example
   *
   * @param string $name the name you wish to test against
   * @return mixed returns nickname if exists, false otherwise
   */
  protected function get_nickname($name) {
    if (preg_match("/[\(|\"].*?[\)|\"]/", $name, $matches)) {
      return $matches[0];
    }
    return false;
  }



  /**
   * Checks word against array of common suffixes
   *
   * @param string $word the single word you wish to test
   * @param string $name full name for context in determining edge-cases
   * @return mixed boolean if false, string if true (returns suffix)
   */
  protected function is_suffix($word, $name) {

    # Ignore periods, normalize case
    $word = str_replace('.', '', strtolower($word));

    # Search the array for our word
    $line_match = array_search($word, array_map('strtolower', $this->dict['suffixes']['line']));
    $prof_match = array_search($word, array_map('strtolower', $this->dict['suffixes']['prof']));

    # Break out for professional suffix matches first
    if ($prof_match !== false) {
      return $this->dict['suffixes']['prof'][$prof_match];
    }

    # Now test our edge cases based on lineage
    if ($line_match !== false) {

      # Store our match
      $matched_case = $this->dict['suffixes']['line'][$line_match];

      # Remove it from the array
      $temp_array = $this->dict['suffixes']['line'];
      unset($temp_array[$line_match]);

      # Make sure we're dealing with the suffix and not a surname
      if ($word == 'senior' || $word == 'junior') {

        # If name is Joshua Senior, it's pretty likely that Senior is the surname
        # However, if the name is Joshua Jones Senior, then it's likely a suffix
        if (str_word_count($name) < 3) {
          return false;
        }

        # If the word Junior or Senior is contained, but so is some other
        # lineage suffix, then the word is likely a surname and not a suffix
        foreach ($temp_array as $suffix) {
          if (preg_match("/\b".$suffix."\b/i", $name)) {
            return false;
          }
        }
      }
      return $matched_case;
    }
    return false;
  }



  /**
   * Checks word against list of common honorific prefixes
   *
   * @param string $word the single word you wish to test
   * @return boolean
   */
  protected function is_salutation($word) {
    $word = str_replace('.', '', strtolower($word));
    foreach ($this->dict['prefix'] as $replace => $originals) {
      if (in_array($word, $originals)) {
        return $replace;
      }
    }
    return false;
  }



  /**
   * Checks our dictionary of compound indicators to see if last name is compound
   *
   * @param string $word the single word you wish to test
   * @return boolean
   */
  protected function is_compound($word) {
    return array_search(strtolower($word), $this->dict['compound']);
  }



  /**
   * Test string to see if it's a single letter/initial (period optional)
   *
   * @param string $word the single word you wish to test
   * @return boolean
   */
  protected function is_initial($word) {
    return ((strlen($word) == 1) || (strlen($word) == 2 && $word{1} == "."));
  }



  /**
   * Checks for camelCase words such as McDonald and MacElroy
   *
   * @param string $word the single word you wish to test
   * @return boolean
   */
  protected function is_camel_case($word) {
    if (preg_match("/[A-Za-z]([A-Z]*[a-z][a-z]*[A-Z]|[a-z]*[A-Z][A-Z]*[a-z])[A-Za-z]*/", $word)) {
      return true;
    }
    return false;
  }

  # ucfirst words split by dashes or periods
  # ucfirst all upper/lower strings, but leave camelcase words alone
  public function fix_case($word) {

    # Fix case for words split by periods (J.P.)
    if (strpos($word, '.') !== false) {
      $word = $this->safe_ucfirst(".", $word);;
    }

    # Fix case for words split by hyphens (Kimura-Fay)
    if (strpos($word, '-') !== false) {
      $word = $this->safe_ucfirst("-", $word);
    }

    # Special case for single letters
    if (strlen($word) == 1) {
      $word = strtoupper($word);
    }

    # Special case for 2-letter words
    if (strlen($word) == 2) {
      # First letter is vowel, second letter consonant (uppercase first)
      if (in_array(strtolower($word{0}), $this->dict['vowels']) && !in_array(strtolower($word{1}), $this->dict['vowels'])) {
        $word = ucfirst(strtolower($word));
      }
      # First letter consonant, second letter vowel or "y" (uppercase first)
      if (!in_array(strtolower($word{0}), $this->dict['vowels']) && (in_array(strtolower($word{1}), $this->dict['vowels']) || strtolower($word{1}) == 'y')) {
        $word = ucfirst(strtolower($word));
      }
      # Both letters vowels (uppercase both)
      if (in_array(strtolower($word{0}), $this->dict['vowels']) && in_array(strtolower($word{1}), $this->dict['vowels'])) {
        $word = strtoupper($word);
      }
      # Both letters consonants (uppercase both)
      if (!in_array(strtolower($word{0}), $this->dict['vowels']) && (!in_array(strtolower($word{1}), $this->dict['vowels']) && strtolower($word{1}) != 'y')) {
        $word = strtoupper($word);
      }
    }

    # Fix case for words which aren't initials, but are all upercase or lowercase
    if ( (strlen($word) >= 3) && (ctype_upper($word) || ctype_lower($word)) ) {
      $word = ucfirst(strtolower($word));
    }

    return $word;
  }

  # helper public function for fix_case
  public function safe_ucfirst($seperator, $word) {
    # uppercase words split by the seperator (ex. dashes or periods)
    $parts = explode($seperator, $word);
    foreach ($parts as $word) {
      $words[] = ($this->is_camel_case($word)) ? $word : ucfirst(strtolower($word));
    }
    return implode($seperator, $words);
  }

}