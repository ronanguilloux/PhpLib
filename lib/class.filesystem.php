<?php
/*
 * FileSystem lib class file
 * Created on 24 avr. 2010 at 11:26:14 by ronan
 *
 * @author Ronan GUILLOUX
 * @license http://www.gnu.org/licenses/gpl.html GNU GPL v3
 * @version 1.0
 * @package PhpLib 
 * @filesource filesystem.http.php
 * @see http://fr.php.net/manual/fr/class.recursivedirectoryiterator.php
 * @see http://fr.php.net/manual/fr/class.directoryiterator.php
 */

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
    public function RemoveSVN( $dir ) {
        echo "Searching: $dir\n\t";

        $flag = false; // haven't found .svn directory
        $svn = $dir . '.svn';

        if( is_dir( $svn ) ) {
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
        while( false !== ( $file = readdir( $handle ) ) ) {
            if( $file == '.' || $file == '..' ) // don't get lost by recursively going through the current or top directory
                continue;

            if( is_dir( $dir . $file ) )
                $this->RemoveSVN( $dir . $file . '/' ); // apply the SVN removal for sub directories
        }
    }

    /**
     * Evaluate a file's extension value
     *
     * @param string filepath
     * @return mixed : a boolean (false) or a valid string
     */
    public static function getExtension($filepath){
        if(!file_exists($filepath)) return false;
        $extension = false;
        $filename = basename($filepath);
        if (strstr($filename, ".")){
            $tmp_extension = explode('.', $filename);
            $tmp_extension = $tmp_extension[count($tmp_extension)-1];
            if($tmp_extension != '' && $tmp_extension != $file_data['name']) {
                $extension = '.'.$tmp_extension;
            }
        }

        return $extension;
    }

    /**
     * Delete a dir
     *
     * precondition: $dir is a valid directory
     * postcondition: $dir and all it's contents are removed
     * @param string $dir
     */
    public static function DelTree( $dir ) {
        $files = glob( $dir . '*', GLOB_MARK ); // find all files in the directory

        foreach( $files as $file ) {
            if( substr( $file, -1 ) == '/' )
                $this->DelTree( $file ); // recursively apply this to sub directories
            else
                unlink( $file );
        }

        if ( is_dir( $dir ) )
            rmdir( $dir ); // remove the directory itself (rmdir only removes a directory once it is empty)
    }

    public static function ListFiles($dir)
    {
        if(is_dir($dir))
        {
            if($handle = opendir($dir))
            {
                while(($file = readdir($handle)) !== false)
                {
                    if($file != "." && $file != ".." && $file != "Thumbs.db")
                    {
                        echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";
                    }
                }
                closedir($handle);
            }
        }
    }

    /**
     * Destroy a dir
     *
     * @param string $dir
     * @param bool $virtual
     */
    public static function DestroyDir($dir, $virtual  = false)
    {
        $ds = DIRECTORY_SEPARATOR;
        $dir = $virtual ? realpath($dir) : $dir;
        $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
        if (is_dir($dir) && $handle = opendir($dir))
        {
            while ($file = readdir($handle))
            {
                if ($file == '.' || $file == '..')
                {
                    continue;
                }
                elseif (is_dir($dir.$ds.$file))
                {
                    destroyDir($dir.$ds.$file);
                }
                else
                {
                    unlink($dir.$ds.$file);
                }
            }
            closedir($handle);
            rmdir($dir);
            return true;
        }
        else
        {
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
    public static function ZipCreate($files = array(),$destination = '',$overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,$file);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
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
    public static function UnzipFile($file, $destination){
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

    /**
     * Get the very most recent file from a $directory
     *
     * Based on the archaic PHP3-style example code, is most likely better than what the OP came up with. ;)
     *
     * @see http://www.computing.net/answers/webdevel/php-sort-directory-contents-by-date/3483.html
     * @param string $directory full path
     * @return string path of the most recent file found in $directory
     */
    public static function GetMostRecentFile($directory)
    {
        $files = glob( $directory.'/*.*' );
        array_multisort( array_map( 'filemtime', $files ), SORT_NUMERIC, SORT_DESC, $files );
        return $files[0];
    }
}
