<?php
/*
 * Http lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource class.http.php
 */

/**
 * HTTP lib class
 * @author Ronan GUILLOUX
 *
 */
class Http
{

    /**
     * Get Client’s real IP address
     *
     * @return IP address
     */
    public static function GetRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * @param $file - path to file
     */
    public static function ForceDownload($file)
    {
        if ((isset($file))&&(file_exists($file))) {
            header("Content-length: ".filesize($file));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            readfile("$file");
        } else {
            echo "No file selected";
        }
    }

    /**
     * Url string validator
     *
     * @param string $url - Url to validate
     */
    public static function Is_valid_url($url)
    {
        if (preg_match('/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i', $url)) {
            return true;
    }
    return false;
    }


    /**
     * Get an title tag inner html valu from url
     *
     * @param string $url
     * @return string title
     */
    public static function GetTitleValue($url)
    {
        $fp = fopen($url,"r");
        while (!feof($fp) ){
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
     * @param string $page html source
     * @return string title
     */
    public static function GetPageTitleFromSource($page) 
    {
        preg_match("/<title>(.*)<\/title>/imsU", $page, $matches);
        return $matches[1];
    }

    /**
     * Get an html tag inner html value
     *
     * @param string $url
     * @param string $tag - such as "body class='web'"
     * @return string title
     */
    public static function GetTagValue($url,$tag)
    {
        $fp = fopen($url,"r");
        while (!feof($fp) ){
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
     * @param int $max-depth, 9 as default value
     * @return array
     */ 
    public static function GetURLs($content, $depth = 0, $max_depth = 9) 
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
    public static function GetLocalization()
    {

        ( ( (float)phpversion() < 5.3 ) ) ? die ( "Fatal error : PHP < 5.3 !, can't proceed in " . __CLASS__ . '::' . __METHOD__ ) : '';
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
     if (Http::DomainCheck($domainName) != -1) {
     echo "Cannot reach the server!" ;
     } else {
     echo "Server's running well." ;
     }
     *   
     * @param string $domainName, ex : http://snipplr.com
     * @return server status
     */
    function static function DomainCheck($domainName)
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
    function static function GetRealIpAddr()
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

}
