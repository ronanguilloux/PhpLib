<?php
/*
 * FileSystem lib class file
 * Created on 24 avr. 2010 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib
 * @filesource Filesystem.php
 * @see http://fr.php.net/manual/fr/class.recursivedirectoryiterator.php
 * @see http://fr.php.net/manual/fr/class.directoryiterator.php
 */

namespace PhpLib;

/**
 * FileSystem lib class
 * @author Ronan GUILLOUX
 *
 */
class FileSystem
{

    /**
     * Remove .svn dirs
     *
     * preconditon: $dir ends with a forward slash (/) and is a valid directory
     * postcondition: $dir and all it's sub-directories are recursively
     * searched through for .svn directories. If a .svn directory is found,
     * it is deleted to remove any security holes.
     * @param string $dir
     * @see http://www.lateralcode.com/remove-svn-php/
     * @tutorial <?php removeSVN( './' ); ?>
     */
    public function removeSVN( $dir )
    {
        echo "Searching: $dir\n\t";

        $flag = false; // haven't found .svn directory
        $svn = $dir . '.svn';

        if ( is_dir( $svn ) ) {
            if( !chmod( $svn, 777 ) )
                echo "File permissions could not be changed (this may or may not be a problem--check the statement below).\n\t"; // if the permissions were already 777, this is not a problem

            $this->DelTree( $svn ); // remove the .svn directory with a helper function

            if( is_dir( $svn ) ) // deleting failed
                echo "Failed to delete $svn due to file permissions.";
            else
                echo "Successfully deleted $svn from the file system.";

            $flag = true; // found directory
        }

        if( !$flag ) // no .svn directory
            echo 'No .svn directory found.';
        echo "\n\n";

        $handle = opendir( $dir );
        while ( false !== ( $file = readdir( $handle ) ) ) {
            if( $file == '.' || $file == '..' ) // don't get lost by recursively going through the current or top directory
                continue;

            if( is_dir( $dir . $file ) )
                $this->removeSVN( $dir . $file . '/' ); // apply the SVN removal for sub directories
        }
    }

    /**
     * Evaluate a file's extension value
     *
     * @param string filepath
     * @return mixed : a boolean (false) or a valid string
     */
    public static function getExtension($filepath)
    {
        if(!file_exists($filepath)) return false;
        if(is_readable($filepath)) return false;

        return pathinfo($filepath, PATHINFO_EXTENSION);
    }

    /**
     * Supprime un repertoire et tout son contenu
     *
     * @param string $dir
     */
    protected function rmdirDeep($dir)
    {
        $nodes = glob($dir.'/*', GLOB_MARK);
        foreach ($nodes as $node) {
            if(is_dir($node))
                $this->rmdirDeep($node);
            else
                unlink($node);
        }

        if(is_dir($dir))
            rmdir($dir);
    }

    /**
     * get human filesize
     *
     * @author http://goo.gl/ZaY0o (a comment in http://www.php.net/filesize)
     * @param  int    $bytes
     * @param  int    $decimals count
     * @return string human readable size
     */
    public static function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    /**
     * Delete a dir
     *
     * precondition: $dir is a valid directory
     * postcondition: $dir and all it's contents are removed
     * @param string $dir
         */
        public static function DelTree( $dir )
        {
            $files = glob( $dir . '*', GLOB_MARK ); // find all files in the directory

            foreach ($files as $file) {
                if( substr( $file, -1 ) == '/' )
                    $this->DelTree( $file ); // recursively apply this to sub directories
                else
                    unlink( $file );
            }

            if ( is_dir( $dir ) )
                rmdir( $dir ); // remove the directory itself (rmdir only removes a directory once it is empty)
        }

        /**
         * Get a dirs-only list (not recursive)
         *
         * @param string $path
         * @return mixed array
         */
        public static function dirs($path)
        {
            $result = array_filter(glob($path.'*'), 'is_dir');

            return (is_array($result)) ? $result : array(); // always returns an array
        }

        public static function listFiles($dir)
        {
            if (is_dir($dir)) {
                if ($handle = opendir($dir)) {
                    while (($file = readdir($handle)) !== false) {
                        if ($file != "." && $file != ".." && $file != "Thumbs.db") {
                            echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";
                        }
                    }
                    closedir($handle);
                }
            }
        }

        /**
         * Recursive Glob (flatting)
         *
         * @param int $pattern the pattern passed to glob()
         * @param int $flags the flags passed to glob()
         * @param string $path the path to scan
         * @example rglob('*.php');
         * @author http://www.php.net/manual/fr/function.glob.php#87221
         * @return mixed - an flat array of files in the given path matching the pattern.
         */
        public static function rglob($path = '', $pattern='*', $flags = 0)
        {
            $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
            $files=glob($path.$pattern, $flags);
            foreach ($paths as $path) {
                $files=array_merge($files,self::rglob($path, $pattern, $flags));
            }

            return $files;
        }

        /**
         * Destroy a dir
         *
         * @param string $dir
         * @param bool $virtual
         */
        public static function destroyDir($dir, $virtual  = false)
        {
            $ds = DIRECTORY_SEPARATOR;
            $dir = $virtual ? realpath($dir) : $dir;
            $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
            if (is_dir($dir) && $handle = opendir($dir)) {
                while ($file = readdir($handle)) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    } elseif (is_dir($dir.$ds.$file)) {
                        destroyDir($dir.$ds.$file);
                    } else {
                        unlink($dir.$ds.$file);
                    }
                }
                closedir($handle);
                rmdir($dir);

                return true;
            } else {
                return false;
            }
        }

        /**
         * Creates a compressed zip file
         *
         * @see http://php.net/manual/fr/class.ziparchive.php
         * @example $files=array('file1.jpg', 'file2.jpg', 'file3.gif');
         * @example create_zip($files, 'myzipfile.zip', true);
         * @param array $files
         * @param string $destination
         * @param bool $overwrite
         */
        public static function zipCreate($files = array(),$destination = '',$overwrite = false)
        {
            //if the zip file already exists and overwrite is false, return false
            if (file_exists($destination) && !$overwrite) { return false; }
                //vars
                $valid_files = array();
            //if files were passed in...
            if (is_array($files)) {
                //cycle through each file
                foreach ($files as $file) {
                    //make sure the file exists
                    if (file_exists($file)) {
                        $valid_files[] = $file;
                    }
                }
            }
            //if we have good files...
            if (count($valid_files)) {
                //create the archive
                $zip = new ZipArchive();
                if ($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach ($valid_files as $file) {
                    $zip->addFile($file,$file);
                }
                //debug
                //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

                //close the zip -- done!
                $zip->close();

                //check to make sure the file exists
                return file_exists($destination);
            } else {
                return false;
            }
        }

        /**
         * Unzip a zip archive
         *
         * @see http://php.net/manual/fr/class.ziparchive.php
         * @param string $file path to zip file
         * @param string $destination destination directory for unzipped files
         */
        public static function unzipFile($file, $destination)
        {
            // create object
            $zip = new ZipArchive() ;
            // open archive
            if ($zip->open($file) !== TRUE) {
                die ('Could not open archive');
            }
            // extract contents to destination directory
            $zip->extractTo($destination);
            // close archive
            $zip->close();
            echo 'Archive extracted to directory';
        }


        // Other unzip method
        public static function unZip($location,$newLocation)
        {
            if (exec("unzip $location",$arr)) {
                mkdir($newLocation);
                for ($i = 1;$i< count($arr);$i++) {
                    $file = trim(preg_replace("~inflating: ~","",$arr[$i]));
                    copy($location.'/'.$file,$newLocation.'/'.$file);
                    unlink($location.'/'.$file);
                }

                return TRUE;
            } else {
                return FALSE;
            }
        }


        // see http://fr.php.net/manual/fr/function.stat.php
        public static function alt_stat($file)
        {
            clearstatcache();
            $ss=@stat($file);
            if(!$ss) return false; //Couldnt stat file

            $ts=array(
                0140000=>'ssocket',
                0120000=>'llink',
                0100000=>'-file',
                0060000=>'bblock',
                0040000=>'ddir',
                0020000=>'cchar',
                0010000=>'pfifo'
            );

            $p=$ss['mode'];
            $t=decoct($ss['mode'] & 0170000); // File Encoding Bit

            $str =(array_key_exists(octdec($t),$ts))?$ts[octdec($t)]{0}:'u';
            $str.=(($p&0x0100)?'r':'-').(($p&0x0080)?'w':'-');
            $str.=(($p&0x0040)?(($p&0x0800)?'s':'x'):(($p&0x0800)?'S':'-'));
            $str.=(($p&0x0020)?'r':'-').(($p&0x0010)?'w':'-');
            $str.=(($p&0x0008)?(($p&0x0400)?'s':'x'):(($p&0x0400)?'S':'-'));
            $str.=(($p&0x0004)?'r':'-').(($p&0x0002)?'w':'-');
            $str.=(($p&0x0001)?(($p&0x0200)?'t':'x'):(($p&0x0200)?'T':'-'));

            $s=array(
                'perms'=>array(
                    'umask'=>sprintf("%04o",@umask()),
                    'human'=>$str,
                    'octal1'=>sprintf("%o", ($ss['mode'] & 000777)),
                    'octal2'=>sprintf("0%o", 0777 & $p),
                    'decimal'=>sprintf("%04o", $p),
                    'fileperms'=>@fileperms($file),
                    'mode1'=>$p,
                    'mode2'=>$ss['mode']),

                'owner'=>array(
                    'fileowner'=>$ss['uid'],
                    'filegroup'=>$ss['gid'],
                    'owner'=>
                    (function_exists('posix_getpwuid'))?
                    @posix_getpwuid($ss['uid']):'',
                        'group'=>
                        (function_exists('posix_getgrgid'))?
                        @posix_getgrgid($ss['gid']):''
                    ),

                    'file'=>array(
                        'filename'=>$file,
                        'realpath'=>(@realpath($file) != $file) ? @realpath($file) : '',
                        'dirname'=>@dirname($file),
                        'basename'=>@basename($file)
                    ),

                    'filetype'=>array(
                        'type'=>substr($ts[octdec($t)],1),
                        'type_octal'=>sprintf("%07o", octdec($t)),
                        'is_file'=>@is_file($file),
                        'is_dir'=>@is_dir($file),
                        'is_link'=>@is_link($file),
                        'is_readable'=> @is_readable($file),
                        'is_writable'=> @is_writable($file)
                    ),

                    'device'=>array(
                        'device'=>$ss['dev'], //Device
                        'device_number'=>$ss['rdev'], //Device number, if device.
                        'inode'=>$ss['ino'], //File serial number
                        'link_count'=>$ss['nlink'], //link count
                        'link_to'=>($sll
                        ['type']=='link') ? @readlink($file) : ''
                    ),

                    'size'=>array(
                        'size'=>$ss['size'], //Size of file, in bytes.
                        'blocks'=>$ss['blocks'], //Number 512-byte blocks allocated
                        'block_size'=> $ss['blksize'] //Optimal block size for I/O.
                    ),

                    'time'=>array(
                        'mtime'=>$ss['mtime'], //Time of last modification
                        'atime'=>$ss['atime'], //Time of last access.
                        'ctime'=>$ss['ctime'], //Time of last status change
                        'accessed'=>@date('Y M D H:i:s',$ss['atime']),
                        'modified'=>@date('Y M D H:i:s',$ss['mtime']),
                        'created'=>@date('Y M D H:i:s',$ss['ctime'])
                    ),
                );

            clearstatcache();

            return $s;
        }

        /**
         * Get the very most recent file from a $directory
         *
         * Based on the archaic PHP3-style example code, is most likely better than what the OP came up with. ;)
         *
         * @see http://www.computing.net/answers/webdevel/php-sort-directory-contents-by-date/3483.html
         * @param string $directory full path
         * @return string path of the most recent file found in $directory
         */
        public static function getMostRecentFile($directory)
        {
            $files = glob( $directory.'/*.*' );
            array_multisort( array_map( 'filemtime', $files ), SORT_NUMERIC, SORT_DESC, $files );

            return $files[0];
        }
}
