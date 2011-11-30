<?php
/** Space Tests class file

 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * Created on 30 2011-11-30 2011 at 21:06
 * @version //autogentag//
 */

class SpaceTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        //
    }

    /**
     * covers Website::__construct
     */
    public function testDistance()
    {
        //$this->markTestSkipped(__METHOD__);
        $this->assertTrue(true);
        $lats = array(51.538, 51.537, '51.539', 51.537, 51.537, 'foo', 51.538, null);
        $longs = array(-0.193, -0.192, '-0.195', '-0,192' , 'bar', -0.195, '', -0.193);
        $expecteds = array('0.91', '0,79', false, false, false, false,false);
        $spacer = new Space();
        $fromLat = 51.53;
        $fromLong = -0.19;
        foreach($expecteds as $i=>$expected){
            $res = Space::distance($fromLat, $fromLong, $lats[$i], $longs[$i]);
            if(is_double($res)) {
                $res = sprintf('%.2f', $res);
            } else {
                $res = false;
            }
            $this->assertEquals($expected, $res);
        }
    }

}
?>

