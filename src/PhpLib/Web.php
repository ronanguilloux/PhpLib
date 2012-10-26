<?php
/*
 * Web lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Web.php
 */

namespace PhpLib;

/**
 * Web lib class
 * @author Ronan GUILLOUX
 *
 */
class Web
{

    /**
     * Build Gravatar's html <img /> using an email
     *
     * @param  string $email   - Email address to show gravatar for
     * @param  int    $size    - Size of gravatar
     * @param  string $default - URL of default gravatar to use
     * @param  string $rating  - Rating of Gravatar(G, PG, R, X)
     * @return html's <img /> filled tag
     */
    public static function showGravatar($email, $size, $default,  $rating)
    {
        return '<img src="http://www.gravatar.com/avatar.php?gravatar_id='.md5($email).
            '&default='.$default.'&size='.$size.'&rating='.$rating.'" width="'.$size.'px"
            height="'.$size.'px" />';
    }

    /**
     * Get website favicon from url
     *
     * @author snipplr.com
     * @param  string $url
     * @return string favicon url
     */
    public static function getFavicon($url)
    {
        $url = str_replace("http://",'',$url);
        //TODO : regex that removes the last part of the url and detects errors
        return "http://www.google.com/s2/favicons?domain=".$url;

    }

    /**
     * Takes a string and makes it SEO and URL friendly
     *
     * @param string $string
     * return SEOized string
     */
    public static function SEOize($string)
    {
        $string = preg_replace("`\[.*\]`U","",$string);
        $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
        $string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
        $string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');return strtolower(trim($string, '-'));

        return $string;
    }

    /**
     * Get GoogleKeyWords used in referer URL
     *
     * @param  string   $referer (null, URL or $_SERVER['HTTP_REFERER'])
     * @return keywords array
     * @see http://webarto.com/12/php-google-keywords
     */
    public static function getGoogleKeyWordUsed($referer = null)
    {
        // see
        if (is_null($referer)) {
            $referer = $_SERVER['HTTP_REFERER'];
        }
        $referer = urldecode($referer);
        if (strstr($referer,"google")) {
            preg_match('/q=([a-zA-Z0-9\s\č\ć\ž\š\đ]+)/i',$referer,$bingo);
            //echo $bingo[0];
            $keywords = str_replace('q=','',$bingo[0]);
            $keywords = explode(' ',$keywords);
            foreach ($keywords as $keyword) {
                $unix = time();
                if (!empty($keyword)) {/*do something, eg insert into database*/};
            }

            return $keywords;
        }
    }

    /**
     * Slugify : Modifies a string to remove all non ASCII characters and spaces.
     *
     * @link http://sourcecookbook.com/en/recipes/8/function-to-slugify-strings-in-php
     * @param string $text
     * @param char
     * @return string slugified_text
     */
    public static function slugify($text, $char = '_')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', $char, $text);
        $text = trim($text, $char);
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^'.$char.'\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function urlize($string)
    {
        $find   = array(
            '/[^A-Za-z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]/'  #alphanum + accents
            ,'/[-]+/'             # multi -
            ,'/(^-)/'             # - as begin
            ,'/(-$)/'             # - as end
        );
        $repl = array('-','-','','');

        return preg_replace($find, $repl, $string);
    }

}
