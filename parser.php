<?php



/**
 * Split a full name into its constituent parts
 *   - prefix/salutation (Mr. Mrs. Dr. etc)
 *   - given/first name
 *   - middle name/initial(s)
 *   - surname (last name)
 *   - surname base (last name without compounds)
 *   - surname compounds (only the compounds)
 *   - suffix (II, PhD, Jr. etc)
 *
 * Author: Josh Fraser
 *
 * Contribution from Clive Verrall www.cliveverrall.com February 2016
 * 
 * // other contributions: 
 * //   - eric willis [list of honorifics](http://notes.ericwillis.com/2009/11/common-name-prefixes-titles-and-honorifics/)
 * //   - `TomThak` for raising issue #16 and providing [wikepedia resource](https://cs.wikipedia.org/wiki/Akademick%C3%BD_titul)
 * //   - `atla5` for closing the issue.
*/
class FullNameParser {



  /**
   * Create the dictionary of terms for use later
   *
   *  - Common honorific prefixes (english)
   *  - Common compound surname identifiers
   *  - Common suffixes (lineage and professional)
   * Note: longer professional titles should appear earlier in the array than shorter titles to reduce the risk of mis-identification e.g. BEng before BE
   * Also note that case and periods are part of the matching for professional titles and therefore need to be correct, there are no case conversions
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
      'Hon.' => array('honorable'),
      'Pres.' => array('president'),
      'Gov' => array('governor','governer'),
      'Ofc' => array('officer'),
      'Msgr' => array('monsignor'),
      'Sr.' => array('sister'),
      'Br.' => array('brother'),
      'Supt.' => array('superintendent'),
      'Rep.' => array('representatitve'),
      'Sen.' => array('senator'),
      'Amb.' => array('ambassador'),
      'Treas.' => array('treasurer'),
      'Sec.' => array('secretary'),
      'Pvt.' => array('private'),
      'Cpl.' => array('corporal'),
      'Sgt.' => array('sargent'),
      'Adm.' => array('administrative','administrator','administrater'),
      'Maj.' => array('major'),
      'Capt.' => array('captain'),
      'Cmdr.' => array('commander'),
      'Lt.' => array('lieutenant'),
      'Lt. Col.' => array('lieutenant colonel'),
      'Col.' => array('colonel'),
      'Gen.' => array('general'),
      'Bc.' => array('bachelor', 'baccalaureus'),
      'BcA.' => array('bachelor of arts', 'baccalaureus artis'),
      'ICDr.' => array('doctor of canon law', 'juris cononici doctor'),
      'Ing.' => array('engineer', 'ingenieur'),
      'Ing. sheet.' => array('architect engineer', 'intrudes upon architectus'),
      'JUDr.' => array('juris doctor utriusque', 'doctor rights'),
      'MDDr.' => array('doctor of dental medicine', 'medicinae doctor dentium'),
      'MgA.' => array('master of arts','magister artis'),
      'Mgr.' => array('master'),
      'MD.' => array('doctor of general medicine'),
      'DVM.' => array('doctor of veterinary medine'),
      'PaedDr.' => array('doctor of education'),
      'PharmDr.' => array('doctor of pharmacy'),
      'PhDr.' => array('doctor of philosophy'),
      'PhMr.' => array('master of pharmacy'),
      'RCDr.' => array('doctor of business studies'),
      'RNDr.' => array('doctor of science'),
      'DSc.' => array('doctor of science'),
      'RSDr.' => array('doctor of socio-political sciences'),
      'RTDr.' => array('doctor of technical sciences'),
      'ThDr.' => array('doctor of theology'),
      'Th.D.' => array('doctor of theology'),
      'ThLic.' => array('licentiate of theology'),
      'ThMgr.' => array('master of theology','master of divinity'),
      'Acad.' => array('academian','academic'),
      'ArtD.' => array('doctor of arts'),
      'DiS.' => array('certified specialist'),
      'As.' => array('assistant'),
      'Odb. As.' => array('assistant professor'),
      'Doc.' => array('associate professor'),
      ' ' => array('the')
    ),
    'compound' => array('da','de','del','della', 'dem', 'den', 'der','di','du', 'het', 'la', 'onder', 'op', 'pietro','st.','st','\'t', 'ten', 'ter','van','vanden','vere','von'),
    'suffixes' => array(
      'line' => array('I','II','III','IV','V','1st','2nd','3rd','4th','5th','Senior','Junior','Jr','Sr'),
      'prof' => array('AO', 'B.A.', 'M.Sc', 'BCompt', 'PhD', 'Ph.D.','APR','RPh','PE','MD', 'M.D.', 'MA','DMD','CME', 'BSc', 'Bsc', 'BSc(hons)', 'Ph.D.', 'BEng', 'M.B.A.', 'MBA', 'FAICD', 'CM', 'OBC', 'M.B.', 'ChB', 'FRCP', 'FRSC',
          'FREng', 'Esq', 'MEng', 'MSc', 'J.D.', 'JD', 'BGDipBus', 'Dip', 'Dipl.Phys','M.H.Sc.', 'MPA', 'B.Comm', 'B.Eng', 'B.Acc', 'FSA', 'PGDM', 'FCPA', 'RN', 'R.N.', 'MSN',
          'PCA', 'PCCRM','PCFP','PCGD','PCHR','PCM','PCPS','PCPM','PCSCM','PCSM','PCMM','PCTC','ACA', 'FCA','ACMA', 'FCMA','AAIA', 'FAIA','CCC','MIPA','FIPA','CIA','CFE','CISA','CFAP',
          'QC', 'Q.C.', 'M.Tech', 'CTA', 'C.I.M.A.', 'B.Ec',
          'CFIA','ICCP','CPS','CAP-OM','CAPTA','TNAOAP','AFA','AVA','ASA','CAIA','CBA','CVA','ICVS','CIIA','CMU','PFM','PRM','CFP','CWM','CCP','EA','CCMT','CGAP','CDFM','CFO','CGFM','CGAT','CGFO','CMFO','CPFO','CPFA',
          'BMD','BIET','P.Eng','PE', 'MBBS', 'MB', 'BCh', 'BAO', 'BMBS', 'MBBChir', 'MBChBa','MPhil','LL.D', 'LLD', 'D.Lit','DEA', 'DESS', 'DClinPsy', 'DSc', 'MRes', 'M.Res', 'Psy.D', 'Pharm.D',
          'BA(Admin)', 'BAcc','BACom','BAdmin','BAE','BAEcon','BA(Ed)','BA(FS)','BAgr','BAH','BAI','BAI(Elect)','BAI(Mech)','BALaw','BAO','BAppSc','BArch','BArchSc','BARelSt','BASc','BASoc', 'DDS', 'D.D.S.',
          'BASS','BATheol','BBA','BBLS', 'BBS','BBus','BChem','BCJ','BCL','BCLD(SocSc)','BClinSci','BCom','BCombSt','BCommEdCommDev','BComp','BComSc','BCoun','BD','BDes','BE','BEcon','BEcon&Fin', 'M.P.P.M.', 'MPPM',
          'BEconSci', 'BEd','BEng','BES','BEng(Tech)','BFA','BFin','BFLS','BFST','BH','BHealthSc','BHSc','BHy','BJur','BL','BLE','BLegSc','BLib','BLing','BLitt','BLittCelt','BLS','BMedSc','BMet',
          'BMid', 'BMin','BMS','BMSc','BMSc','BMS','BMus','BMusEd','BMusPerf','BN', 'BNS','BNurs','BOptom','BPA','BPharm', 'BPhil', 'TTC', 'DIP', 'Tchg', 'BEd', 'MEd','ACIB', 'FCIM', 'FCIS', 'FCS', 'Fcs',
          'Bachelor', 'O.C.', 'JP', 'C.Eng', 'C.P.A.', 'B.B.S.', 'MBE', 'GBE', 'KBE', 'DBE', 'CBE', 'OBE', 'MRICS',  'D.P.S.K.', 'D.P.P.J.', 'DPSK', 'DPPJ', 'B.B.A.', 'GBS', 'MIGEM', 'M.I.G.E.M.', 'FCIS',    
          'BPhil(Ed)', 'BPhys','BPhysio','BPl','BRadiog','BSc', 'B.Sc', 'BScAgr','BSc(Dairy)','BSc(DomSc)','BScEc','BScEcon','BSc(Econ)','BSc(Eng)','BScFor','BSc(HealthSc)','BSc(Hort)', 'BBA', 'B.B.A.',
          'BSc(MCRM)', 'BSc(Med)','BSc(Mid)','BSc(Min)','BSc(Psych)', 'BSc(Tech)','BSD', 'BSocSc','BSS','BStSu','BTchg','BTCP','BTech','BTechEd','BTh','BTheol','BTS','EdB','LittB','LLB','MA','MusB','ScBTech', 
          'CEng', 'FCA', 'CFA', 'Cfa', 'C.F.A.', 'LLB', 'LL.B', 'LLM', 'LL.M', 'CA(SA)', 'C.A.', 'CA','CPA',  'Solicitor',  'DMS', 'FIWO', 'CEnv', 'MICE', 'MIWEM', 'B.Com', 'BCom', 'BAcc', 'BA', 'BEc', 'MEc', 'HDip', 'B.Bus.', 'E.S.C.P.' )
    ),
    'vowels' => array('a','e','i','o','u')
  );

  protected $not_nicknames = array( "(hons)");


  /**
   * Parse Static entry point.
   *
   * @param string $name the full name you wish to parse
   * @return array returns associative array of name parts
   */
  public static function parse($name) {
    $parser = new self();
    return $parser->parse_name($name);
  }


  /**
   * This is the primary method which calls all other methods
   *
   * @param string $name the full name you wish to parse
   * @return array returns associative array of name parts
   */
  public function parse_name($full_name) {

    # Remove leading/trailing whitespace
    $full_name = trim($full_name);

    // remove any words that don't add value
    // $full_name = str_replace("(Hons)", '', $full_name );
    // $full_name = str_replace("(hons)", '', $full_name );

    # Setup default vars
    extract(array('salutation' => '', 'fname' => '', 'initials' => '', 'lname' => '', 'lname_base' => '', 'lname_compound' => '', 'suffix' => ''));

    # Find all the professional suffixes possible
    $professional_suffix = $this->get_pro_suffix($full_name);

    // The position of the first professional suffix denotes the end of the name and the start of suffixes
    $first_suffix_index = mb_strlen($full_name);
    foreach ($professional_suffix as $key => $psx) {
      $start = mb_strpos($full_name, $psx);
      if( $start === FALSE ) {
        echo "ASSERT ERROR, the professional suffix:".$psx." cannot be found in the full name:".$full_name."<br>";
        continue;
      }
      if( $start < $first_suffix_index) {
        $first_suffix_index = $start;
      }
    }

    // everything to the right of the first professional suffix is part of the suffix
    $suffix = mb_substr($full_name, $first_suffix_index);

    // remove the suffixes from the full_name
    $full_name = mb_substr($full_name, 0, $first_suffix_index);

    # Deal with nickname, push to array
    $has_nick = $this->get_nickname($full_name);
    if ($has_nick) {
      # Remove wrapper chars from around nickname
      $name['nickname'] = mb_substr($has_nick, 1, (mb_strlen($has_nick) - 2));
      # Remove the nickname from the full name
      $full_name = str_replace($has_nick, '', $full_name);
      # Get rid of consecutive spaces left by the removal
      $full_name = str_replace('  ', ' ', $full_name);
    }
    
    # Grab a list of words from the remainder of the full name
    $unfiltered_name_parts = $this->break_words($full_name);

    # Is first word a title or multiple titles consecutively?
    if( count($unfiltered_name_parts)) {
      // only start looking if there are any words left in the name to process
      while (count($unfiltered_name_parts) > 0 && $s = $this->is_salutation($unfiltered_name_parts[0])) {
        $salutation .= "$s ";
        array_shift($unfiltered_name_parts);
      }
      $salutation = trim($salutation);
      // Find if there is a line suffix, if so then move it out
      # Is last word a suffix or multiple suffixes consecutively?
      while (count($unfiltered_name_parts) > 0 && $s = $this->is_line_suffix($unfiltered_name_parts[count($unfiltered_name_parts)-1], $full_name)) {
        if( $suffix != "") {
          $suffix = $s.", ".$suffix;
        } else {
          $suffix .= $s;
        }
        array_pop($unfiltered_name_parts);
      }
      $suffix = trim($suffix);
    } else {
      $salutation = "";
      $suffix = "";
    }

    // Re-pack the unfiltered name parts array and exclude empty words
    $name_arr = array();
    foreach ($unfiltered_name_parts as $key => $name_part) {
      $name_part = trim($name_part);
      $name_part = rtrim($name_part,',');
      if(mb_strlen($name_part) == '1') {
        // If any word left is of one character that is not alphabetic then it is not a real word, so remove it
        if( ! $this->mb_ctype_alpha($name_part)) {
          $name_part = "";
        }
      }
      if(mb_strlen(trim($name_part)) ) {
        $name_arr[] = $name_part;
      }
    }
    $unfiltered_name_parts = $name_arr;

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
            $fname .= " ".mb_strtoupper($word);
          }
          else {
            $initials .= " ".mb_strtoupper($word);
          }
        }
        # otherwise, just go ahead and save the initial
        else {
          $initials .= " ".mb_strtoupper($word);
        }
      }
      else {
        $fname .= " ".$this->fix_case($word);
      }
    }

    if( count($unfiltered_name_parts)) {
      # check that we have more than 1 word in our string
      if ($end-0 > 1) {
        # concat the last name and split last name in base and compound
        for ($i; $i < $end; $i++) {
          if ($this->is_compound($unfiltered_name_parts[$i])) {
            $lname_compound .= " ".$unfiltered_name_parts[$i];
          } else {
            $lname_base .= " ".$this->fix_case($unfiltered_name_parts[$i]);
          }
          $lname .= " ".$this->fix_case($unfiltered_name_parts[$i]);
        }
      }
      else {
        # otherwise, single word strings are assumed to be first names
        $fname = $this->fix_case($unfiltered_name_parts[$i]);
      }
    } else {
      $fname = "";
    }

    # return the various parts in an array
    $name['salutation'] = $salutation;
    $name['fname'] = trim($fname);
    $name['initials'] = trim($initials);
    $name['lname'] = trim($lname);
    $name['lname_base'] = trim($lname_base);
    $name['lname_compound'] = trim($lname_compound);
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
    $temp_word_arr = explode(' ', $name);
    $final_word_arr = array();
    foreach ($temp_word_arr as $key => $word) {
      if( $word != "" && $word != ",") {
        $final_word_arr[] = $word;
      }
    }
    return $final_word_arr;
  }



  /**
   * Checks for the existence of, and returns professional suffix
   *
   * @param string $name the name you wish to test
   * @return mixed returns the suffix if exists, false otherwise
   */
  public function get_pro_suffix($name) {

    $found_suffix_arr = array();
    foreach ($this->dict['suffixes']['prof'] as $suffix) {
      if (preg_match('/[,\s]+'.preg_quote($suffix).'\b/i', $name, $matches)) {
        $found_suffix = trim($matches[0]);
        $found_suffix = rtrim($found_suffix,',');
        $found_suffix = ltrim($found_suffix,',');
        $found_suffix_arr[] = trim($found_suffix);
      }
    }
    return $found_suffix_arr;
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
      if( ! in_array( mb_strtolower($matches[0]), $this->not_nicknames ) ) {
        return $matches[0];
      } else {
        return false;
      }
    }
    return false;
  }



  /**
   * Checks word against array of common lineage suffixes
   *
   * @param string $word the single word you wish to test
   * @param string $name full name for context in determining edge-cases
   * @return mixed boolean if false, string if true (returns suffix)
   */
  protected function is_line_suffix($word, $name) {

    # Ignore periods and righ commas, normalize case
    $word = str_replace('.', '', mb_strtolower($word));
    $word = rtrim($word,',');

    # Search the array for our word
    $line_match = array_search($word, array_map('mb_strtolower', $this->dict['suffixes']['line']));

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
        if ($this->mb_str_word_count($name) < 3) {
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
    $word = str_replace('.', '', mb_strtolower($word));
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
    return in_array(mb_strtolower($word), $this->dict['compound']);
  }



  /**
   * Test string to see if it's a single letter/initial (period optional)
   *
   * @param string $word the single word you wish to test
   * @return boolean
   */
  protected function is_initial($word) {
    return ((mb_strlen($word) == 1) || (mb_strlen($word) == 2 && $word{1} == "."));
  }



  /**
   * Checks for camelCase words such as McDonald and MacElroy
   *
   * @param string $word the single word you wish to test
   * @return boolean
   */
  protected function is_camel_case($word) {
    if (preg_match('/\p{L}(\p{Lu}*\p{Ll}\p{Ll}*\p{Lu}|\p{Ll}*\p{Lu}\p{Lu}*\p{Ll})\p{L}*/', $word)) {
      return true;
    }
    return false;
  }

  # ucfirst words split by dashes or periods
  # ucfirst all upper/lower strings, but leave camelcase words alone
  public function fix_case($word) {

    # Fix case for words split by periods (J.P.)
    if (mb_strpos($word, '.') !== false) {
      $word = $this->safe_ucfirst(".", $word);;
    }

    # Fix case for words split by hyphens (Kimura-Fay)
    if (mb_strpos($word, '-') !== false) {
      $word = $this->safe_ucfirst("-", $word);
    }

    # Special case for single letters
    if (mb_strlen($word) == 1) {
      $word = mb_strtoupper($word);
    }

    # Special case for 2-letter words
    if (mb_strlen($word) == 2) {
      # Both letters vowels (uppercase both)
      if (in_array(mb_strtolower($word{0}), $this->dict['vowels']) && in_array(mb_strtolower($word{1}), $this->dict['vowels'])) {
        $word = mb_strtoupper($word);
      }
      # Both letters consonants (uppercase both)
      if (!in_array(mb_strtolower($word{0}), $this->dict['vowels']) && !in_array(mb_strtolower($word{1}), $this->dict['vowels'])) {
        $word = mb_strtoupper($word);
      }
      # First letter is vowel, second letter consonant (uppercase first)
      if (in_array(mb_strtolower($word{0}), $this->dict['vowels']) && !in_array(mb_strtolower($word{1}), $this->dict['vowels'])) {
        $word = $this->mb_ucfirst(mb_strtolower($word));
      }
      # First letter consonant, second letter vowel or "y" (uppercase first)
      if (!in_array(mb_strtolower($word{0}), $this->dict['vowels']) && (in_array(mb_strtolower($word{1}), $this->dict['vowels']) || mb_strtolower($word{1}) == 'y')) {
        $word = $this->mb_ucfirst(mb_strtolower($word));
      }
    }

    # Fix case for words which aren't initials, but are all uppercase or lowercase
    if ( (mb_strlen($word) >= 3) && ($this->mb_ctype_upper($word) || $this->mb_ctype_lower($word)) ) {
      $word = $this->mb_ucfirst(mb_strtolower($word));
    }

    return $word;
  }

  # helper public function for fix_case
  public function safe_ucfirst($seperator, $word) {
    # uppercase words split by the seperator (ex. dashes or periods)
    $parts = explode($seperator, $word);
    foreach ($parts as $word) {
      $words[] = ($this->is_camel_case($word)) ? $word : $this->mb_ucfirst(mb_strtolower($word));
    }
    return implode($seperator, $words);
  }

    # helper public function for multibytes ctype_alpha
    public function mb_ctype_alpha($text)
    {
      return (bool)preg_match('/^\p{L}*$/', $text);
    }

    # helper public function for multibytes ctype_lower
    public function mb_ctype_lower($text)
    {
      return (bool)preg_match('/^\p{Ll}*$/', $text);
    }

    # helper public function for multibytes ctype_upper
    public function mb_ctype_upper($text)
    {
      return (bool)preg_match('/^\p{Lu}*$/', $text);
    }

    # helper public function for multibytes str_word_count
    public function mb_str_word_count($text)
    {
      if (empty($text)) {
        return 0;
      } else {
        return preg_match('/s+/', $text) + 1;
      }
    }

    # helper public function for multibytes ucfirst
    public function mb_ucfirst($string)
    {
      $strlen = mb_strlen($string);
      $firstChar = mb_substr($string, 0, 1);
      $then = mb_substr($string, 1, $strlen - 1);
      return mb_strtoupper($firstChar) . $then;
    }
}
