<?php
/** Isocodes Tests class file

 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * Created on 2013-03-27 at 15:13
 * @version //autogentag//
 */

namespace PhpLib\Tests;

use PhpLib\Isocodes;
use IsoCodes\Siret;

class IsocodesTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        //
    }

    /**
     * covers Isocodes::getSiret
     */
    public function testGetSiret()
    {
        $siret = Isocodes::getSiret();
        $this->assertTrue( Siret::validate( $siret ));
    }

    /**
     * covers Isocodes::getSiret
     */
    public function testGetSiretWithBlanks()
    {
        $siret = Isocodes::getSiret(true);
        // blanks are not exepcted by validator...
        $this->assertFalse( Siret::validate( $siret ));
        // ... but:
        $this->assertTrue( Siret::validate( str_replace(' ', '', $siret) ));
    }

}
