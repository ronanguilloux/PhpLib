<?php
/*
 * Dates lib class file
 * Created on 24 avr. 2010 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Dates.php
 */

namespace PhpLib;

/**
 * Dates lib class
 * @author Ronan GUILLOUX
 *
 */
class Dates
{

    /**
     * Convert timestamp to Solr date
     * See also: http://www.w3.org/TR/xmlschema-2/#dateTime
     *
     * @param int Timestamp
     * @return string Solr datetime
     */
    public static function convertTimestampToSolrDate( $timestamp )
    {
        return strftime( '%Y-%m-%dT%H:%M:%S.000Z', (int) $timestamp );
    }

    /**
     * Returns the duration of the given time period in days, hours, minutes and seconds.
     * e.g. secsToStr(1234567) would return “14 days, 6 hours, 56 minutes, 7 seconds”
     */
    public static function secsToStr($secs)
    {
        if ($secs>=86400) {$days=floor($secs/86400);$secs=$secs%86400;$r=$days.' day';if ($days<>1) {$r.='s';}if ($secs>0) {$r.=', ';}}
            if ($secs>=3600) {$hours=floor($secs/3600);$secs=$secs%3600;$r.=$hours.' hour';if ($hours<>1) {$r.='s';}if ($secs>0) {$r.=', ';}}
                if ($secs>=60) {$minutes=floor($secs/60);$secs=$secs%60;$r.=$minutes.' minute';if ($minutes<>1) {$r.='s';}if ($secs>0) {$r.=', ';}}
                    $r.=$secs.' second';if ($secs<>1) {$r.='s';}

                    return $r;
    }

    /**
     * checkDateFormat : Validate a date in “YYYY-MM-DD” format
     *
     * @param  mixed $date
     * @return void
     */
    public static function checkDateFormat($date)
    {
        //match the format of the date
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //check weather the date is valid of not
            if(checkdate($parts[2],$parts[3],$parts[1]))

                return true;
            else
                return false;
        } else

            return false;
    }

}
