<?php

use Composer\Installer\PackageEvent;

trait nomad_composerFunctions
{
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


    public static function composerPostPackageInstall(PackageEvent $event)
    {
        self::parseComposerInstallerPackageEvent($event, $name, $dir,$vendordir, $projectdir);
        $siterootdir = dirname($projectdir);
        #echo "installed $name '$dir' $projectdir\n";
        if($name==='nomadit/nomad'){
            // nomad install - prepare various required dirs for theme install
            // includes already exists - thats the composer projectdir
            mkdir("$siterootdir/includes/themes", true);
            mkdir("$siterootdir/assets", true);
            // copy in various files.. todo
        }
        if(strpos($name,'nomadit/nomad-theme-')===0){
            // theme install - copy files into place
            self::copyRecursive("$dir/assets", "$siterootdir/assets", 1);
            self::copyRecursive("$dir/includes", "$siterootdir/includes", 1);
        }
    }


    public static function composerPostPackageUpdate(PackageEvent $event)
    {
        self::parseComposerInstallerPackageEvent($event, $name, $dir,$vendordir, $projectdir);
        $siterootdir = dirname($projectdir);
        // can autoload previously installed packages from here (update) #$autoloader = include "$vendordir/autoload.php";
        #echo "updated $name '$dir' $projectdir\n";
        if(strpos($name,'nomadit/nomad-theme-')===0){
            // theme update - copy files into place
            self::copyRecursive("$dir/assets", "$siterootdir/assets", 1);
            self::copyRecursive("$dir/includes", "$siterootdir/includes", 1);
        }
    }


    /**
     * with installer $event, parse out useful vars
     * also see https://stackoverflow.com/questions/47046250/how-do-you-get-the-package-name-from-a-composer-event
     *
     * @param PackageEvent $event
     * @param $name string  name of package eg nomadit/nomad
     * @param $dir string   dir where package has been installed
     * @param $vendordir string
     * @param $projectdir string  the dir above vendordir
     */
    protected static function parseComposerInstallerPackageEvent(PackageEvent $event,
                                                                 &$name, &$dir, &$vendordir, &$projectdir )
    {
        /** @var InstallOperation|UpdateOperation $operation */
        $operation = $event->getOperation();
        $package = method_exists($operation, 'getPackage')
            ? $operation->getPackage()
            : $operation->getInitialPackage();

        $name = $package->getName();
        $dir  = $event->getComposer()->getInstallationManager()->getInstallPath($package);
        $vendordir   = $event->getComposer()->getConfig()->get('vendor-dir');
        $projectdir  = dirname($vendordir);
    }



    /*
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
    */

    protected static function symlink($target, $link)
    {
        // check existing link is correct
        if (file_exists($link)) {
            if (linkinfo($link) === linkinfo($target)) {
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

    protected static function copyRecursive($srcDir, $dstDir, $overwrite = null)
    {
        $flags = "-r "; // -r recursive
        if (!$overwrite) $flags .= "-n "; // -n no-clobber (don't overwrite existing)
        $cmd = "cp $flags $srcDir/. $dstDir";  // use . not * to glob onto all files
        exec($cmd, $output, $r);
        echo "$r $cmd\n";
    }

    protected static function getPathsInDir($dir)
    {
        $paths = array();
        if ($dh = @opendir($dir)) {
            while (($n = readdir($dh)) !== false) {
                if ($n != "." && $n != "..") {
                    $path = $dir . '/' . $n;

                    if (is_file($path)) {
                        $paths[] = $path;
                    }
                    if (is_dir($path)) {
                        $paths[] = $path . '/'; // distinguish by ending with /
                    }
                }
            }
            closedir($dh);
        }
        return $paths;
    }
}