<?php
/*
 * Geo lib class file
 * Created on april 1st 2011 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Geo.php
 */

namespace PhpLib;

/**
 * Geo lib class
 * @author Ronan GUILLOUX
 *
 */
class Geo
{

    /**
     * Distance
     * Source : http://derickrethans.nl/spatial-indexes-data-sqlite.html
     *
     * @param  float  $latA, ex : 51.5375
     * @param  float  $lonA, ex : -0.1933
     * @param  float  $latB
     * @param  float  $lonB
     * @return double distance in km, can be formatted using sprintf('%.2f', $res)
     */
    public static function distance($latA, $lonA, $latB, $lonB)
    {
        if (!is_float($latA) || !is_float($lonA) || !is_float($latB) || !is_float($lonB)) {
            return false;
        }

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
    public static function kmDistance($lat1, $lon1, $lat2, $lon2, $unit = 'k')
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } elseif ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * Geocoding, from address to coordinates lat long
     *
     * @author snipplr.com
     * @param  string $address, postal address
     * @return array  coords
     */
    public static function getLatLong($address)
    {
        if (!is_string($address))die("All Addresses must be passed as a string");
        $_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
        $_result = false;
        if ($_result = file_get_contents($_url)) {
            if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
            preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
            $_coords['lat'] = $_match[1];
            $_coords['long'] = $_match[2];
        }

        return $_coords;
    }

    const GOOGLE_GEOCODE_API = "http://maps.googleapis.com/maps/api/geocode";
    const GOOGLE_MATRIX_API = "http://maps.googleapis.com/maps/api/distancematrix";
    const GOOGLE_QUERY = "address=";
    const GOOGLE_GEOCODE_OPTIONS = "&language=fr&sensor=false";
    const GOOGLE_MATRIX_OPTIONS = "&mode=driving&language=fr-FR&sensor=false";
    const GOOGLE_SESSION_QUERIESMAX = 50; // each geocode/geotravel max queries

    public static $geocodeInvalidStatus = array(
        'ZERO_RESULTS',
        'REQUEST_DENIED',
        'INVALID_REQUEST',
        'OVER_QUERY_LIMIT'
    );

    public static $matrixInvalidStatus = array(
        'INVALID_REQUEST',
        'MAX_ELEMENTS_EXCEEDED',
        'OVER_QUERY_LIMIT',
        'REQUEST_DENIED',
        'UNKNOWN_ERROR'
    );

    /**
     * Geocode
     *
     * @param  string                          $rawAddress : complete address to resolve as lat/long
     * @param  string                          $provider   : the API to ask, google by default
     * @param  string                          $output     : output format, xml by default
     * @return array('anwser'=>array(lat,lng), 'status'=>OK/BAD)
     */
    public function geocode($rawAddress, $provider = 'google', $output = 'xml')
    {
        $rawAddress = $this->prepareForUrlApiCalls($rawAddress);
        $result = false;
        switch ($provider) {
            // other possible provider : Yahoo place
        default:
            $url = GeoHelper::GOOGLE_GEOCODE_API . "/$output?" . GeoHelper::GOOGLE_QUERY;
            $url .= $rawAddress . GeoHelper::GOOGLE_GEOCODE_OPTIONS;
            $result = $this->askGoogleGeocode($url, $output);
            break;
        }

        return $result;
    }

    public function geotravel($origin, $destinations = array(),
        $provider = 'google', $output = 'xml')
    {
        $result = false;
        $separator = '|';
        $origin = $this->prepareForUrlApiCalls($origin);
        $destinationsQuery = '';
        foreach ($destinations as $index => $destination) {
            if ($index > 0) {
                $destinationsQuery .= $separator;
            }
            $destination = $destination['rawAddress'];
            $destinationsQuery .= $this->prepareForUrlApiCalls($destination);
        }
        switch ($provider) {
            // other possible provider : Yahoo place
        default:
            $url = GeoHelper::GOOGLE_MATRIX_API . "/$output?";
            $url .= "origins=$origin&destinations=$destinationsQuery";
            $url .= GeoHelper::GOOGLE_MATRIX_OPTIONS;
            echo "\n $url";
            $result = $this->askGoogleDistance($url, $output);
            break;
        }

        return $result;
    }

    protected function prepareForUrlApiCalls($str)
    {
        // convert ALL whitespace to a single space,
        // including lone \t and \n - see http://goo.gl/ZlD0
        //$rawAddress = urlencode($rawAddress);
        $str = preg_replace("'\s+'", ' ', $str);

        // convert ALL whitespace to a '+', since google map api need valid url
        return str_replace(' ', '+', strip_tags($str));
    }

    /**
     * Ask a remote API & return a ['result'=>foo, 'status'=>bar] array
     *
     * @param  string               $url    : complete URL to be cUrled
     * @param  string               $output : output format, xml by default
     * @return array('answer'=>foo, 'status'=>bar)
     */
    protected function askGoogleGeocode($url, $output = 'xml')
    {
        $status = false;
        $answer = array();

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $content = trim(curl_exec($c));
        curl_close($c);
        switch ($output) {
            // other possible way : json
        default: // 'xml'
            $content = simplexml_load_string($content);
            // raw string typecasting required on SXE object
            $status = (string) $content->status;
            if (!empty($content->result->geometry->location)) {
                $answer = (array) $content->result->geometry->location;
            }
            break;
        }

        return array(
            'answer'=>$answer,
            'status'=>$status
        );
    }

    /**
     * Ask a remote API & return a ['result'=>foo, 'status'=>bar] array
     * Ex : http://goo.gl/3Mf35
     *
     * @param  string               $url    : complete URL to be cUrled
     * @param  string               $output : output format, xml by default
     * @return array('answer'=>foo, 'status'=>bar)
     */
    protected function askGoogleDistance($url, $output = 'xml')
    {
        $status = false;
        $answer = array();

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $content = trim(curl_exec($c));
        curl_close($c);
        switch ($output) {
            // other possible way : json
        default: // 'xml'
            $content = simplexml_load_string($content);
            // raw string typecasting required on SXE object
            $status = (string) $content->status;
            if (!empty($content->row)) {
                $answer = (array) $content->row;
            }
            break;
        }

        return array(
            'answer'=>$answer,
            'status'=>$status
        );
    }
}
