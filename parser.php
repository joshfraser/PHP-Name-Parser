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
    'compound' => array('vere','von','van','de','del','della','di','da','pietro','vanden','du','st.','st','la','ter'),
    'suffixes' => array(
      'line' => array('I','II','III','IV','V','Senior','Junior','Jr','Sr'),
      'prof' => array('PhD','APR','RPh','PE','MD','MA','DMD','CME')
    )
  );



  /**
   * This is the primary method which calls all other methods
   *
   * @param string $name the full name you wish to parse
   * @param boolean $nick whether or not to return nicknames (false = discard)
   * @return array returns associative array of name parts
   */
  public function parse_name($full_name, $nick=false) {

    # Remove leading/trailing whitespace
    $full_name = trim($full_name);

    # Setup default vars
    extract(array('salutation' => '', 'fname' => '', 'initials' => '', 'lname' => '', 'suffix' => ''));

    # Grab a list of words from name
    $unfiltered_name_parts = $this->break_words($full_name);

    # If list contains professional suffix, pop it off
    $professional_suffix = (isset($unfiltered_name_parts['pro_suffix'])) ? array_pop($unfiltered_name_parts) : null;

    # Deal with nickname, push to array
    foreach ($unfiltered_name_parts as $word) {
      if ($this->is_nickname($word)) {
        if ($nick) {
          # Remove whatever characters are wrapped around this string.
          $word = substr($word, 1, (strlen($word) - 2));
          # Associate the nickname
          $name['nickname'] = $word;
        }
      }
      else {
        $name_parts[] = $word;
      }
    }
    
    # Is first word a title or multiple titles consecutively?
    while ($s = $this->is_salutation($name_parts[0])) {
      $salutation .= "$s ";
      array_shift($name_parts);
    }
    $salutation = trim($salutation);

    # Is last word a suffix or multiple suffixes consecutively?
    while ($s = $this->is_suffix($name_parts[count($name_parts)-1], $full_name)) {
      $suffix .= "$s ";
      array_pop($name_parts);
    }
    $suffix = trim($suffix);

    # If suffix and professional suffix not empty, add comma
    if (!empty($professional_suffix) && !empty($suffix)) {
      $suffix .= ', ';
    }

    # Concat professional suffix to suffix
    $suffix .= $professional_suffix;
    
    # set the ending range after prefix/suffix trim
    $end = sizeof($name_parts);
    
    # concat the first name
    for ($i=0; $i<$end-1; $i++) {
      $word = $name_parts[$i];
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
          if ($this->is_initial($name_parts[$i+1])) {
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
    if ($end-$start > 1) {
      # concat the last name
      for ($i; $i < $end; $i++) {
        $lname .= " ".$this->fix_case($name_parts[$i]);
      }
    }
    else {
      # otherwise, single word strings are assumed to be first names
      $fname = $this->fix_case($name_parts[$i]);
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
   * Breaks name into individual words while also associating
   * any professional suffixes. (ie. PhD, MD, etc)
   *
   * @param string $name the full name you wish to parse
   * @return array full list of words broken down by spaces
   */
  public function break_words($name) {

    # Check for the existence of professional suffixes
    # Example: Thomas P. Jones III, PhD (PhD is pro suffix)
    if (preg_match("/(.*), *(.*)/", $name, $matches)) {
      $name  = $matches[1];
      $pro_suffix = $matches[2];
    }
    else {
      $pro_suffix = null;
    }

    # Now that we've removed the pro suffix (if applicable)
    # we can blow up the string into an array of parts
    $parts = explode(' ', $name);

    # If we found an pro suffix, add it to the array
    if (isset($pro_suffix)) {
      $parts['pro_suffix'] = $pro_suffix;
    }

    return $parts;
  }

  /**
   * Limited function to check for nicknames based on a few checks
   *  - String wrapped in parentheses (string)
   *  - String wrapped in double quotes "string"
   *  - String wrapped in single quotes 'string'
   *
   * @param string $word the word you wish to test
   * @return boolean
   */
  protected function is_nickname($word) {

    # Define last char index
    $end = strlen($word) - 1;

    # Ensures the word is wrapped with either parentheses, double quotes, or single quotes
    if (($word{0} == '"' && $word{$end} == '"') || ($word{0} == "(" && $word{$end} == ")") || ($word{0} == "'" && $word{$end} == "'")) {
      return true;
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
    if (preg_match("/(?:[A-Z]|[a-z]+)+(?:[A-Z][a-z]+)+/", $word)) {
      return true;
    }
    return false;
  }

  # ucfirst words split by dashes or periods
  # ucfirst all upper/lower strings, but leave camelcase words alone
  public function fix_case($word) {
      # uppercase words split by dashes, like "Kimura-Fay"
      $word = $this->safe_ucfirst("-",$word);
      # uppercase words split by periods, like "J.P."
      $word = $this->safe_ucfirst(".",$word);
      return $word;
  }

  # helper public function for fix_case
  public function safe_ucfirst($seperator, $word) {
      # uppercase words split by the seperator (ex. dashes or periods)
      $parts = explode($seperator,$word);
      foreach ($parts as $word) {
          $words[] = ($this->is_camel_case($word)) ? $word : ucfirst(strtolower($word));
      }
      return implode($seperator,$words);
  }

}