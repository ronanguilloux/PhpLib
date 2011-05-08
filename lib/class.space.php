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
        
}
