<?php
/** Isocodes class file
 * Created on 2013-03-27 at 15:13
 * @author Ronan - @arno_u_loginlux
 */

namespace PhpLib;

/**
 * Isocodes lib class
 * @author Annelies B.
 *
 */
class Isocodes {

    public static function getSiret($withBlanks = false) {
        $siret = '';
        $sum = 0;
        for($i=0;$i!=8;$i++) {
            $rand = mt_rand(0,9);
            $siret.=$rand;
            // odd: add result once ; even: add result twice
            $tmp = $rand * (1+($i+1)%2);
            if ($tmp>=10) $tmp-=9;
            $sum+=$tmp;
        }
        $siret.="0000";
        // fill mandatory "0" digits in order to build a 10 digit number
        $diff = 10-($sum%10);
        if ($diff>2) {
            $first = floor($diff/3);
            $second = $diff-(2*$first);
            $siret.=$first.$second;
        } else {
            $siret.='0'.$diff;
        }

        if ($withBlanks) {
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{5})/", "$1 $2 $3 $4", $siret);
        }

        return $siret;
    }
}
