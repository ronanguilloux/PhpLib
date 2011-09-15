<?php
/*
 * Dates lib class file
 * Created on 24 avr. 2010 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource class.dates.php
 */

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
        return strftime( '%Y-%m-%dT%H:%M:%S.000Z', (int)$timestamp );
    }

}
