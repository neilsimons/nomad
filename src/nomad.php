<?php
/**/

/**
 * nomad_varsAndSetup       basic vars derived and set in setup() and basic accessors
 * nomad_configVars         hughs functions
 * nomad_templateFunctions  hughs functions
 * nomad_devTesting         print_vars etc
 */

// require traits (so no autoloader required)
require_once __DIR__.DIRECTORY_SEPARATOR.'nomad_testing.php';           // for testing & dev
require_once __DIR__.DIRECTORY_SEPARATOR.'nomad_templateFunctions.php'; // modfified versions of Hugh's functions

// require global functions
require_once __DIR__.DIRECTORY_SEPARATOR.'nomad_globalFunctions.inc.php';

/**
 * Class nomad
 * container class for some basic templating functionality
 */
class nomad
{
    // class functionality broken down into traits for separation during dev / debugging
    use nomad_testing;
    use nomad_templateFunctions;


    // internal base vars : derived and set in ::init()
    protected static $siteBaseDir = ''; // the dir of this website's root (as opposed to the webroot of the server)
    protected static $includesDir = '';
                                              // /dir/dir/site/dir1/dir2/page.ext
    protected static $pathComponentSite = ''; // /dir/dir/site
    protected static $pathComponentDirs = ''; //              /dir1/dir2
    protected static $pathComponentPage = ''; //                        /page.ext

    protected static $schemeHostPort = ''; // eg   http://xyz.com:8080  |  https://xyz.com



    // config vars - set in site-options.php
    protected static $preferredPageExt = null; // null=no-change, ''=none, '.hptml' etc
    public static function setPreferredPageExt($ext){
        if($ext!=='' && substr($ext,0,1)!=='.'){ $ext = '.' . $ext; } // ensure ext includes leading '.'
        self::$preferredPageExt = $ext;
    }
    protected static $homePath = '/'; // can be changed to eg "/home" if a one-off landing page is used for "/" eg on shiftingstates.info
    public static function setHomePath($path){
        self::$homePath = $path;
    }
    protected static $defaultDateFmt = 'j M Y'; //
    public static function setDateFmt($fmt){
        self::$defaultDateFmt = $fmt;
    }
    // for generating markup for copying to conference suite (hosted on separate domain), we need to generate fully qualified urls
    protected static $menuLinkPrefixOverride = null;
    public static function setMenuLinkPrefixOverride($menuLinkPrefixOverride){
        self::$menuLinkPrefixOverride = $menuLinkPrefixOverride;
    }
    protected static $pageStyles = ['style-light','style-dark']; // first is default
    public static function setPageStyles(array $pageStyles){ self::$pageStyles = $pageStyles; }
    protected static $menuArray = [];
    public static function setMenuArray(array $menuArray){ self::$menuArray = $menuArray; }
    protected static $theme = 'ss'; // default theme
    public static function setTheme($theme){ self::$theme = $theme; }

    // public accessors
    public static function siteBaseDir(){       return self::$siteBaseDir; }
    public static function includesDir(){       return self::$includesDir; }
    public static function schemeHostPort(){    return self::$schemeHostPort; }
    public static function sitePath(){          return self::$pathComponentSite; } // just the /sitesubdir/s   '' for none

    public static function theme(){             return self::$theme; }


    // protected accessors
    protected static function homePath(){       return self::$homePath; }

    // public set* to override vars derived by initVars() if fails to work correctly
    public static function setSitePath($sitePath){ self::$pathComponentSite = $sitePath; }




    // building on above basics, piece together other functions



    public static function pageModifiedDate($fmt=null){
        if(!isset($fmt)) $fmt = self::$defaultDateFmt;
        return date( $fmt, filemtime($_SERVER['SCRIPT_FILENAME']));
    }


    protected static function pageFilename($ext=null){ // return currentPage's filename with specified file-extension
        if(!isset($ext)) $ext = self::$preferredPageExt;
        if(isset($ext) && $ext!=='' && substr($ext,0,1)!=='.'){ $ext = '.' . $ext; } // ensure ext includes leading '.'

        $pageFileName = substr(self::$pathComponentPage, 1); // remove the leading '/' from /filename.ext

        if(!isset($ext))  return $pageFileName; // return "filename.ext" asis

        return pathinfo($pageFileName, PATHINFO_FILENAME) . $ext; // with specified ext
    }


    public static function pagePath($ext=null){ // full path to page eg        /sitebasedir /team/john / bio.phtml
        return self::$pathComponentSite . self::$pathComponentDirs . '/' . self::pageFilename($ext);
    }

    public static function pagePathFromSiteRoot($ext=null){ // path without /sitebasedir,   just  /team/john/ bio [.phtml]
        return self::$pathComponentDirs . '/' . self::pageFilename($ext);
    }

    #public static function pageUrl($ext=null){ // full url to page includng http[s]://{hostname}
    #    return self::schemeHostPort().self::pagePath($ext);
    #}

    public static function resourcePath($resourcePath, $versioned=0){
        if($versioned){
            $file = self::siteBaseDir().$resourcePath;
            $mtime = @filemtime($file); // dont err if not found!
            if(!$mtime) $mtime = 'fileNotFound'; // exposes the issue when viewing in browsers devtools
            return self::sitePath().$resourcePath.'?v='.$mtime;
        }
        return self::sitePath().$resourcePath;
    }
    public static function versionedResourcePath($resourcePath){ return self::resourcePath($resourcePath, true); }



    /**
     * run once at start
     * - given the filepath to the sites basedir, derive and set useful vars
     *
     * @param string $siteBaseDir
     * @throws Exception
     */
    public static function initVars( $siteBaseDir )
    {
        $hasrun=0; if($hasrun) return; $hasrun=1;

        self::$siteBaseDir = $siteBaseDir;
        self::$includesDir = $siteBaseDir.'/includes';
        #self::$templatesDir = $siteBaseDir.'/includes';
        // check validity of siteBasedir by looking for includes directory
        if(!file_exists(self::$siteBaseDir) || !is_dir(self::$siteBaseDir)) throw new Exception(__CLASS__." misconfiguration! - invalid \$siteBaseDir dir provided: '".self::$siteBaseDir."'");


        // derive location relative to webroot, siteroot etc..

        // the path-component describing the page file, eg  /page.phtml
        self::$pathComponentPage = '/'.basename(realpath($_SERVER['SCRIPT_FILENAME']));

        // the path-component to reach the page dir eg /subdir
        $realdir_diff = str_replace( self::$siteBaseDir, '', dirname(realpath($_SERVER['SCRIPT_FILENAME'])));
        self::$pathComponentDirs = $realdir_diff;

        // diff of these forms the path-component to reach the site-root (ie if in a sub-dir on webserver) eg /sitedir
        $dir_diff = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME']));
        $diff = str_replace($realdir_diff, '',$dir_diff);
        self::$pathComponentSite = $diff;

        // setup other vars

        // http{s}://host{:port}
        if(isset($_SERVER['REQUEST_SCHEME'])){
            self::$schemeHostPort = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        } else {
            $protocol = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http' ;
            self::$schemeHostPort = $protocol . '://' . $_SERVER['HTTP_HOST'];
        }
    }


    /**
     * run once at start
     * - registers error & exception handlers
     * - starts output buffering - required by error handler, also allows possibility of registering a shutdown function to search replace content
     */
    public static function setupErrorHandling(){
        // start output buffer
        ob_start();
        // convert errors to error exceptions
        set_error_handler( ['nomad','error_handler'] );
        // handle exception - shows the error, then outputs the output buffer for clarity
        set_exception_handler( ['nomad','exception_handler'] );
    }




    /**
     * set as error handler to convert errors to ErrorExceptions
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return bool
     * @throws ErrorException
     */
    public static function error_handler($errno, $errstr, $errfile, $errline)
    {
        if(0==($errno & error_reporting())){ // honour error_reporting() setings and @ usage
            return true; // false = dont report/log
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline );
    }

    /**
     * set as exception handler so error/exception info can be displayed cleanly
     * without being hidden in half generated html
     * @param throwable $e
     */
    public static function exception_handler( $e )
    {
        $output = ob_get_clean();

        #$e_class = get_class($e);
        #$e_msg	 = $e->getMessage();
        $e_code	 = $e->getCode();

        header('HTTP/1.1 500 Internal Server Error', true);
        header('Content-type: text/plain', true);
        // no cache headers
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0", true);
        header("Cache-Control: post-check=0, pre-check=0", true);
        header("Pragma: no-cache", true);

        echo "Error 500 : Internal Server Error\n\n";
        echo "$e_code $e\n";
        echo "\n\n";
        echo $output;
        #echo htmlspecialchars($e)."\n";
    }


    public static function getThemeFileList($theme = null){
        if(!$theme) $theme = self::$theme;
        $basedir     = self::siteBaseDir();
        $sitepath    = self::sitePath();
        $siteroot    = $basedir.$sitepath;
        $relsrchpaths = [
            "includes/themes/$theme",
            "res-nomad/$theme",
            "res-custom/$theme",
        ];
        // todo
    }


    // composer hooks and related functions  (triggered on composer install/update)
    public static function composer_post_install($event){ return self::composerHook($event); }
    public static function composer_post_update($event){ return self::composerHook($event); }
    public static function composerHook($event){
        $name = $event->getName();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $siteRootDir = realpath($vendorDir.'/../..'); // is 2 levels above vendor dir, eg {siteroot}/includes/vendor


        // symlink dirs/files from (root of) vendor/nomadit/nomad/resources/symlink to siteroot
        $pathsToSymlink = self::getPathsInDir($vendorDir.'/nomadit/nomad/resources/symlink');
        foreach($pathsToSymlink as $target){
            $link = $siteRootDir.'/'.basename(rtrim($target,'/'));
            self::symlink( $target, $link );
        }

        // recursively copy files NOT overwriting from  vendor/nomadit/nomad/resources/copy to siteroot
        self::copyRecursive($vendorDir.'/nomadit/nomad/resources/copy', $siteRootDir );

        // recursively copy & overwrite files from  vendor/nomadit/nomad/resources/copy-overwrite to siteroot
        self::copyRecursive($vendorDir.'/nomadit/nomad/resources/copy-overwrite', $siteRootDir, true ); // overwrite
    }

    protected static function symlink($target, $link){
        // check existing link is correct
        if (file_exists($link)) {
            if(linkinfo($link) === linkinfo($target)){
                echo "ignoring existing correct link $link\n";
                return null; // same inodes = existing link is good
            } else {
                unlink($link); // delete incorrect link
                echo "deleting incorrect link.. ";
            }
        }
        // symlink
        echo "symlinking to $target from $link\n";
        return symlink($target, $link);
    }

    protected static function copyRecursive($srcDir, $dstDir, $overwrite=null){
        $flags = "-r "; // -r recursive
        if(!$overwrite) $flags .= "-n "; // -n no-clobber (don't overwrite existing)
        $cmd = "cp $flags $srcDir/. $dstDir";  // use . not * to glob onto all files
        exec($cmd,$output, $r);
        echo "$cmd $r\n";
    }
    protected static function getPathsInDir($dir){
        $paths = array();
        if($dh = @opendir($dir)){
            while( ($n=readdir($dh)) !== false  ){
                if($n!= "." && $n!= ".."){
                    $path = $dir.'/'.$n;

                    if(is_file($path)){
                        $paths[] = $path;
                    }
                    if(is_dir($path)){
                        $paths[] = $path.'/'; // distinguish by ending with /
                    }
                }
            }
            closedir($dh);
        }
        return $paths;
    }

}



