<?php
/*
 * Http lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Http.php
 */

namespace PhpLib;

/**
 * HTTP lib class
 * @author Ronan GUILLOUX
 *
 */
class Http
{

    /**
     * Force Download
     *
     * @author Alessio Delmonti
     * @param $file - path to file
     */
    public static function forceDownload($file, $filename = null)
    {
        $extension = "";

        // Fixing filename value
        if (!isset($filename)) {
            $filename = basename($file);
        }

        if ((isset($file))&&(file_exists($realpath(file)))) {
            $file = realpath($file);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $realPath);
            header("Content-length: ".filesize($file));
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile("$file");
        } else {
            echo "<html><p>$file not found.</p></html>";
        }
    }

    /**
     * Url string validator
     *
     * @param string $url - Url to validate
     */
    public static function is_valid_url($url)
    {
        if (preg_match('/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i', $url)) {
            return true;
        }

        return false;
    }

// TODO : is_IP() using '^[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}$'

    /**
     * Get an title tag inner html valu from url
     *
     * @param  string $url
     * @return string title
     */
    public static function getTitleValue($url)
    {
        $fp = fopen($url,"r");
        while (!feof($fp) ) {
            $page .= fgets($fp, 4096);
        }
        $title = eregi("<title>(.*)</title>",$page,$regs);
        $title = $regs[1];
        fclose($fp);

        return $title;
    }

    /**
     * Get title tag content from html source
     *
     * @param  string $page html source
     * @return string title
     */
    public static function getPageTitleFromSource($page)
    {
        preg_match("/<title>(.*)<\/title>/imsU", $page, $matches);

        return $matches[1];
    }

    /**
     * Get an html tag inner html value
     *
     * @param  string $url
     * @param  string $tag - such as "body class='web'"
     * @return string title
     */
    public static function getTagValue($url,$tag)
    {
        $fp = fopen($url,"r");
        while (!feof($fp) ) {
            $page .= fgets($fp, 4096);
        }
        $title = eregi("<".$tag.">(.*)</".$tag.">",$page,$regs);
        $title = $regs[1];
        fclose($fp);

        return $title;
    }

    /**
     * Return URLs from a content (such as an html source)
     *
     * @param string $content html
     * @param int depth, 0 as default value
     * @param  int   $max-depth, 9 as default value
     * @return array
     */
    public static function getURLs($content, $depth = 0, $max_depth = 9)
    {
        if ($depth >= $max_depth) return array();
        $matches = array();
        $URL_pattern = "/\s+href\s*=\s*[\"\']?([^\s\"\']+)[\"\'\s]+/ims";
        preg_match_all ($URL_pattern, $content, $matches, PREG_PATTERN_ORDER);

        return $matches[1];

    }

    /**
     * Get localization using Google trick
     *
     * @return localization
     */
    public static function getLocalization()
    {

        ( ( (float) phpversion() < 5.3 ) ) ? die ( "Fatal error : PHP < 5.3 !, can't proceed in " . __CLASS__ . '::' . __METHOD__ ) : '';
        $site = file_get_contents( "http://www.google.fr/search?q=VBXMCBVFKJSHDKHDKF" );
        $getLocationViaGoogle = function ( $html ){
            $regex = "#<\w+\s\w+=\"tbos\">([^<]{3,})<\/\w+>#i";
            preg_match_all( $regex, $html, $matches );

            return $matches[1][0];
        };

        return $getLocationViaGoogle( $site );
    }

    /**
     * Check a domain
     *
     * @example
     if (Http::domainCheck($domainName) != -1) {
         echo "Cannot reach the server!" ;
} else {
    echo "Server's running well." ;
}
*
    * @param string $domainName, ex : http://snipplr.com
    * @return server status
     */
    public static function domainCheck($domainName)
    {
        $startTime = microtime(true);
        $openDomain = fsockopen ($domainName, 80, $errno, $errstr, 10);
        $finishTime  = microtime(true);
        $serverStatus    = 0;

        if (!$openDomain) $serverStatus = -1;
        else {
            fclose($openDomain);
            $status = ($finishTime - $startTime) * 1000;
            $serverStatus = floor($serverStatus);
        }

        return $serverStatus;
    }

    /**
     * Get Real IP Address, using $_SERVER array, HTTP_* values
     *
     * @return IP value
     */
    public static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Uses cURL to retrieve an url and keep session information
     *
     * @link http://stackoverflow.com/questions/1082302/file-get-contents-from-url-that-is-only-accessible-after-log-in-to-website
     */
    protected static function getUrl($url, $method='', $vars='')
    {
        $ch = curl_init();
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        $buffer = curl_exec($ch);
        curl_close($ch);

        return $buffer;
    }

    /**
     * Base64 Encode
     */
    public static function base64url_encode($plainText)
    {
        $base64 = base64_encode($plainText);
        $base64url = strtr($base64, '+/=', '-_,');

        return $base64url;
    }

    /**
     * Base64 Decode
     */
    public static function base64url_decode($plainText)
    {
        $base64url = strtr($plainText, '-_,', '+/=');
        $base64 = base64_decode($base64url);

        return $base64;
    }

}
