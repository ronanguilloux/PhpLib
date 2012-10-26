<?php
/** String Tests class file

 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * Created on 30 2011-11-30 2011 at 21:06
 * @version //autogentag//
 */

namespace PhpLib\Tests;

use PhpLib\Strings;

class StringsTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        //
    }

    /**
     * covers Website::__construct
     */
    public function testFullUpper()
    {
        $this->assertEquals(Strings::fullUpper('chaudière'), 'CHAUDIÈRE');
    }

}
