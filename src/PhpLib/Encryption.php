<?php
/**
 * Encryption lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Encryption.php
 */

namespace PhpLib;

/**
 * Crypto lib class
 * @author Ronan GUILLOUX
 *
 */
class Encryption
{
    /**
     * @param string $list
     * @param int    $chrs
     */
    public static function passwordCreate($list = "0123456789ABCDEF", $chrs = 8)
    {
        mt_srand((double) microtime()*1000000);
        $generated = '';
        while ( strlen( $generated )< $chrs ) {
            $generated .= $list[mt_rand(0, strlen($list)-1)];
        }

        return $generated;
    }

    public static function randomStringCreate($string = "abcdefghijklmnopqrstuvwxyz0123456789", $length=25)
    {
        for ($i=0;$i<$length;$i++) {
            $pos = rand(0,36);
            $str .= $string[$pos];
        }

        return $str;
    }

    /**
     * Encrypt a text using Mcrypt
     *
     * @see http://maxime-ohayon.developpez.com/tutoriels/mcrypt
     * @param string $algorithm, ex : MCRYPT_3DES, MCRYPT_BLOWFISH, etc. see Mcrpyt doc
     * @param string $mode,      ex : MCRYPT_MODE_ECB, MCRYPT_MODE_NOFB, etc. see Mcrpyt doc
     * @param string $key        = your secret key or passphrase
     * @param string $text       : your text to be encrypted
     */
    public static function encrypt($algorithm = MCRYPT_BLOWFISH, $mode = MCRYPT_MODE_NOFB, $key = "mySecretKey", $text = "textToEncrypt")
    {
        $key_size = mcrypt_module_get_algo_key_size($algorithm);
        $iv_size = mcrypt_get_iv_size($algorithm, $mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $key = substr($key, 0, $key_size);

        return mcrypt_encrypt($algorithm, $key, $text, $mode, $iv);

    }

    /**
     * Decrypt a text using Mcrypt
     *
     * @see http://maxime-ohayon.developpez.com/tutoriels/mcrypt
     * @param string $algorithm, ex : MCRYPT_3DES, MCRYPT_BLOWFISH, etc. see Mcrpyt doc
     * @param string $mode,      ex : MCRYPT_MODE_ECB, MCRYPT_MODE_NOFB, etc. see Mcrpyt doc
     * @param string $key        = your secret key or passphrase
     * @param string $encrypted  : your value to be decrypted
     */
    public static function decrypt($algorithm = MCRYPT_BLOWFISH, $mode = MCRYPT_MODE_NOFB, $key = "mySecretKey", $encrypted = "encryptedText")
    {
        $key_size = mcrypt_module_get_algo_key_size($algorithm);
        $iv_size = mcrypt_get_iv_size($algorithm, $mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $key = substr($key, 0, $key_size);

        return mcrypt_decrypt($algorithm, $key, $cyphered, $mode, $iv);
    }

    /**
     *   random string token generator
     */
    public static function randomToken()
    {
        $string = "abcdefghijklmnopqrstuvwxyz0123456789";
        for ($i=0;$i<25;$i++) {
            $pos = rand(0,36);
            $str .= $string{$pos};
        }

        return $str;
    }

}
