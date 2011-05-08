<?php
/*
 * Email lib class file
 * Created on 2 may at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource class.email.php
 */

/**
 * Email lib class
 * @author Ronan GUILLOUX
 *
 */
class Email
{

    /**
     * Send a simple mail
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $body
     */
    public static function SendSimpleMail($from,$to,$subject,$body)
    {
        $headers = "From: $from\r\n";
        $headers .= "Reply-To: $from\r\n";
        $headers .= "Return-Path: $from\r\n";
        $headers .= "X-Mailer: PHP5\n";
        $headers .= 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        @mail($to,$subject,$body,$headers);
    }

    /**
     * Email string validator
     * 
     * @param string $email - email to validate
     * @return bool
     */
    public static function Is_Email($email)
    {
        return filter_var($email,FILTER_VALIDATE_EMAIL);    
    }
    
    

}
