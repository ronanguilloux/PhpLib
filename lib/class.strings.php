<?php
/**
 * Strings class file
 * Created on 24 avr. 2010 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource class.strings.php
 */

/**
 * Strings lib class
 * @author : mostly php.net contributors
 *
 */
class Strings
{

    /**
     * Capitalize all words
     * @param string Data to capitalize
     * @param string Word delimiters
     * @return string Capitalized words
     * ucwords('clermont-ferrand') == 'Clermont-ferrand' ... fixing it :
     * @see http://fr.php.net/manual/en/function.ucwords.php
     */
    public static function CapitalizeWords($words, $charList = null) {
        // Use ucwords if no delimiters are given
        if (!isset($charList)) {
            return ucwords($words);
        }

        // Go through all characters
        $capitalizeNext = true;

        for ($i = 0, $max = strlen($words); $i < $max; $i++) {
            if (strpos($charList, $words[$i]) !== false) {
                $capitalizeNext = true;
            } else if ($capitalizeNext) {
                $capitalizeNext = false;
                $words[$i] = strtoupper($words[$i]);
            }
        }

        return $words;
    }

    /**
     * lcwords v1.000
     * Convert the first word character to lowercase (opposite to ucwords)
     * input string
     * return string
     */
    public static function Lcwords($string)
    {
        $a = 0;
        $string_new = array();
        $string_exp = explode(" ",$string);
        foreach($string_exp as $astring)
        {
            for($a=0;$a<strlen($astring);$a++)
            {
                /* 
                 * check that the character we are at {pos $a} is a word
                 * i.e. if the word was !A the code would fail at !
                 * then loop to the next character and succeed at A
                 * check at character position $a
                 */
                if(preg_match("'\w'",$astring[$a]))
                {
                    $astring[$a] = strtolower($astring[$a]);
                    break;
                }
            }
            $string_new[] = $astring;
        }
        return implode(" ",$string_new);
    }

    /**
     * Convert unwanted smart quotes.
     *
     * @param smartquoted $string
     * @return unsmartquoted
     * @see http://shiflett.org/blog/2005/oct/convert-smart-quotes-with-php
     */
    protected function convert_smart_quotes($string)
    {
        $search = array(chr(145),
                chr(146),
                chr(147),
                chr(148),
                chr(151));

        $replace = array("'",
                "'", 
                '"', 
                '"', 
                '-'); 

        return str_replace($search, $replace, $string);
    }
}
