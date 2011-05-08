<?php
/*
 * Web2 lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource class.web2.php
 */

/**
 * Web2 lib class
 * @author Ronan GUILLOUX
 *
 */
class Web2
{

    /**
     * Build Gravatar's html <img /> using an email
     *
     * @param string $email - Email address to show gravatar for
     * @param int $size - Size of gravatar
     * @param string $default - URL of default gravatar to use
     * @param string $rating - Rating of Gravatar(G, PG, R, X)
     * @return html's <img /> filled tag
     */
    public static function ShowGravatar($email, $size, $default,  $rating)
    {
        return '<img src="http://www.gravatar.com/avatar.php?gravatar_id='.md5($email).
        '&default='.$default.'&size='.$size.'&rating='.$rating.'" width="'.$size.'px"
        height="'.$size.'px" />';
    }

    /**
     * Takes a string and makes it SEO and URL friendly
     * 
     * @param string $string
     * return SEOized string
     */
    public static function SEOize($string){
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
     * @param string $referer (null, URL or $_SERVER['HTTP_REFERER'])
     * @return keywords array
     * @see http://webarto.com/12/php-google-keywords
     */
    public static function GetGoogleKeyWordUsed($referer = null)
    {
        // see
        if(is_null($referer)) {
            $referer = $_SERVER['HTTP_REFERER'];
        }
        $referer = urldecode($referer);
        if(strstr($referer,"google")){
            preg_match('/q=([a-zA-Z0-9\s\č\ć\ž\š\đ]+)/i',$referer,$bingo);
            //echo $bingo[0];
            $keywords = str_replace('q=','',$bingo[0]);
            $keywords = explode(' ',$keywords);
            foreach ($keywords as $keyword) {
                $unix = time();
                if(!empty($keyword)){/*do something, eg insert into database*/};
            }
            return $keywords;
        }
    }
}
