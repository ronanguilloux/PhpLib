<?php
/*
 * Space lib class file
 * Created on april 1st 2011 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource class.space.php
 */

/**
 * Space lib class
 * @author Ronan GUILLOUX
 *
 */
class Space
{

    /**
     * Distance
     * Source : http://derickrethans.nl/spatial-indexes-data-sqlite.html
     *
     * @param double $latA, ex : 51.5375
     * @param double $lonA, ex : -0.1933
     * @param double $latB
     * @param double $lonB
     * @return unknown
     */
    public static function Distance($latA, $lonA, $latB, $lonB)
    {
        // convert from degrees to radians
        $latA = deg2rad($latA); $lonA = deg2rad($lonA);
        $latB = deg2rad($latB); $lonB = deg2rad($lonB);

        // calculate absolute difference for latitude and longitude
        $dLat = ($latA - $latB);
        $dLon = ($lonA - $lonB);

        // do trigonometry magic
        $d =
            sin($dLat/2) * sin($dLat/2) +
            cos($latA) * cos($latB) * sin($dLon/2) *sin($dLon/2);
        $d = 2 * asin(sqrt($d));
        return $d * 6371;
    }

    /**
     * Calculate the distance from a point A to a point B, using latitudes and longitudes.
     * The function can return the distance in miles, kilometers, or nautical miles
     *
     * @author zipcodeworld   
     * @example echo distance(32.9697, -96.80322, 29.46786, -98.53506, "k")." kilometers";
     */
    public static function KmDistance($lat1, $lon1, $lat2, $lon2, $unit = 'k') 
    { 

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * Geocoding, from address to coordinates lat long
     *
     * @author snipplr.com
     * @param string $address, postal address
     * @return array coords
     */
    public static function getLatLong($address)
    {
        if (!is_string($address))die("All Addresses must be passed as a string");
        $_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
        $_result = false;
        if($_result = file_get_contents($_url)) 
        {
            if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
            preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
            $_coords['lat'] = $_match[1];
            $_coords['long'] = $_match[2];
        }
        return $_coords;
    }
