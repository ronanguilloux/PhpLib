<?php
/**
 * Strings class file
 * Created on 24 avr. 2010 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Strings.php
 */

namespace PhpLib;

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
    public static function capitalizeWords($words, $charList = null)
    {
        // Use ucwords if no delimiters are given
        if (!isset($charList)) {
            return ucwords($words);
        }

        // Go through all characters
        $capitalizeNext = true;

        for ($i = 0, $max = strlen($words); $i < $max; $i++) {
            if (strpos($charList, $words[$i]) !== false) {
                $capitalizeNext = true;
            } elseif ($capitalizeNext) {
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
    public static function lcWords($string)
    {
        $a = 0;
        $string_new = array();
        $string_exp = explode(" ",$string);
        foreach ($string_exp as $astring) {
            for ($a=0;$a<strlen($astring);$a++) {
                /*
                 * check that the character we are at {pos $a} is a word
                 * i.e. if the word was !A the code would fail at !
                 * then loop to the next character and succeed at A
                 * check at character position $a
                 */
                if (preg_match("'\w'",$astring[$a])) {
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
     * @param  smartquoted   $string
     * @return unsmartquoted
     * @see http://shiflett.org/blog/2005/oct/convert-smart-quotes-with-php
     */
    public static function convertSmartQuotes($string)
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

    /**
     * Get the text between $start and $end
     *
     * @author jonasjohn.de
     * @param  string $content (all content)
     * @param  string $start
     * @param  string $end
     * @return string
     */
    public static function getBetween($content, $start, $end)
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);

            return $r[0];
        }

        return '';
    }

    /**
     * NiceImplode : Implode an associative array to a nice formatted string
     *
     * @param mixed $assoc_array
     * @link http://fr2.php.net/implode
     * @return string imploded associative array
     */
    public static function niceImplode($assoc_array)
    {
        $new_array = array_map(create_function('$key, $value', 'return $key.":".$value." --- ";'), array_keys($assoc_array), array_values($assoc_array));

        return implode($new_array);
    }

    public static function array_merge_recursive_simple($Arr1, $Arr2)
    {
        foreach ($Arr2 as $key => $Value) {
            if(array_key_exists($key, $Arr1) && is_array($Value))
                $Arr1[$key] = self::array_merge_recursive_simple($Arr1[$key], $Arr2[$key]);

            else
                $Arr1[$key] = $Value;

        }

        return $Arr1;

    }

    /**
     * Return (bool) if $x contains $y
     *
     * @author jonasjohn.de
     * @param  string  $str
     * @param  string  $content
     * @param  bool    $ignorecase
     * @return boolean
     */
    public static function contains($str, $content, $ignorecase = true)
    {
        if ($ignorecase) {
            $str = strtolower($str);
            $content = strtolower($content);
        }

        return strpos($content,$str) ? true : false;
    }

    public static function sanitize($entry)
    {
        if (is_string($entry)) {
            return htmlspecialchars(trim($entry));
        }

        return $entry;
    }

    /**
     * Modifies a string to remove all non ASCII characters and spaces.
     * Note : Works with UTF-8
     * @link http://antoine.goutenoir.com/blog/2010/10/11/php-slugify-a-string/
     * @param  string $string The text to slugify
     * @return string The slugified text
     */
    public static function slugify($string)
    {
        $string = utf8_decode($string);
        $string = html_entity_decode($string);

        $a = 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ';
        $b = 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn';
        $string = strtr($string, utf8_decode($a), $b);

        $ponctu = array("?", ".", "!", ",");
        $string = str_replace($ponctu, "", $string);

        $string = trim($string);
        $string = preg_replace('/([^a-z0-9]+)/i', '-', $string);
        $string = strtolower($string);

        if (empty($string)) return 'n-a';
        return utf8_encode($string);
    }

    /**
     * Removes all linebreaks
     *
     * @link http://antoine.goutenoir.com/blog/2010/10/11/php-slugify-a-string/
     * @param  string $string The text to be processed.
     * @return string The given text without any linebreaks.
     */
    public static function remove_linebreaks ($string)
    {
        return (string) str_replace(array("\r", "\r\n", "\n"), '', $string);
    }

    /**
     * is_html : Tests a string & check if it's html
     *
     * @param  mixed  $string
     * @return string
     */
    public static function isHtml($string)
    {
        if (strlen($string) == strlen(strip_tags($string))) {
            return false;
        }

        return true;
    }

    /**
     * @see static::fullUper()
     */
    public static function ucfirstHTMLentity($matches)
    {
        return "&".ucfirst(strtolower($matches[1])).";";
    }


    /**
     * PHP's strtoupper() enhanced, with accents convertion trick
     * ex : strtotupper('chaudière') = 'CHAUDIèRE'
     * ex : fullUpper('chaudière') = 'CHAUDIÈRE'
     *
     * @uses static::ucfirstHTMLentity()
     * @param string a string containing accents
     * @return string a better uppercase string
     */
    public static function fullUpper($str)
    {
        $subject = strtoupper(htmlentities($str, null, 'UTF-8'));
        $pattern = '/&([A-Z]+);/';

        return html_entity_decode(preg_replace_callback($pattern, "self::ucfirstHTMLentity", $subject), null, 'UTF-8');
    }

}
