<?php


trait nomad_testing
{

    public static function print_vars()
    {
        #strpos();
        echo "<pre>\n";
        echo __CLASS__."::\$preferredPageExt -> '". ((isset(self::$preferredPageExt))?self::$preferredPageExt:'null') ."'\n";
        echo __CLASS__."::\$homePath         -> '".self::$homePath."'\n";
        echo "\n";
        echo __CLASS__."::\$pathComponentSite -> '".self::$pathComponentSite."'\n";
        echo __CLASS__."::\$pathComponentDirs -> '".self::$pathComponentDirs."'\n";
        echo __CLASS__."::\$pathComponentPage -> '".self::$pathComponentPage."'\n";
        echo "\n";
        echo __CLASS__."::pageFilename(null)    -> '".self::pageFilename(null)."'\n";
        echo __CLASS__."::pageFilename('')      -> '".self::pageFilename('')."'\n";
        echo __CLASS__."::pageFilename('.html') -> '".self::pageFilename('.html')."'\n";
        echo "\n";
        echo __CLASS__."::pagePath(null)        -> '".self::pagePath(null)."'\n";
        echo __CLASS__."::pagePath('')          -> '".self::pagePath('')."'\n";
        echo __CLASS__."::pagePath('.html')     -> '".self::pagePath('.html')."'\n";
        echo "\n";
        echo __CLASS__."::pagePathFromSiteRoot(null)    -> '".self::pagePathFromSiteRoot(null)."'\n";
        echo __CLASS__."::pagePathFromSiteRoot('')      -> '".self::pagePathFromSiteRoot('')."'\n";
        echo __CLASS__."::pagePathFromSiteRoot('.html') -> '".self::pagePathFromSiteRoot('.html')."'\n";
        echo "\n";
        #echo __CLASS__."::pageUrl(null)    -> '".self::pageUrl(null)."'\n";
        #echo __CLASS__."::pageUrl('')      -> '".self::pageUrl('')."'\n";
        #echo __CLASS__."::pageUrl('.html') -> '".self::pageUrl('.html')."'\n";
        #echo "\n";

        $resource = "/testfile.txt";
        if(file_exists(self::siteBaseDir().$resource)){
            $fmt = '<a href="%s">%s</a>';
            $url = nomad::sitePath().$resource;             echo "nomad::sitePath().'$resource'             -> ".sprintf($fmt, $url, $url)."\n";
            $url = nomad::versionedResourcePath($resource); echo "nomad::versionedResourcePath('$resource') -> ".sprintf($fmt, $url, $url)."\n";
        }


        // invole all public static get* methods which dont require args (ie accessors)
        $ignoreList = [
            'print_vars',
            'print_page_url',
            'print_server_vars',
            'print_root_test_links',
        ];
        if(!isset($_GLOBALS['styles'])){
            $ignoreList[] = 'getPageStyle';
            $ignoreList[] = 'getPageStylesJavascript';
        }
        $ReflectionClass = new ReflectionClass(__CLASS__);
        $ReflectionMethods = $ReflectionClass->getMethods(ReflectionMethod::IS_PUBLIC );
        foreach($ReflectionMethods as $ReflectionMethod){ #continue;
            $methodName = $ReflectionMethod->getName();
            if(in_array($methodName,$ignoreList)) continue; // ignore these
            #if(strpos($methodName,'get')!==0) continue; // only get* accessors
            #if(strpos($methodName,'dev_')===0) continue; //
            $requiredParamCount = $ReflectionMethod->getNumberOfRequiredParameters();
            if($requiredParamCount===0){
                $result = $ReflectionMethod->invoke( null, null );
                if(is_array($result)) $result = var_export($result,1);
                #$result = '<span style="color:Grey">'.htmlentities($result).'</span>';
                $result = ''.htmlentities($result).'';
                echo __CLASS__."::$methodName() -> '$result'\n";
            }
        }
        echo "</pre>\n";
    }

    public static function print_server_vars(){
        echo "<pre>\n";
        echo "_REQUEST:"; print_r($_REQUEST);
        echo "_SERVER:"; print_r($_SERVER);
        echo "_COOKIE:"; print_r($_COOKIE);
        echo "</pre>\n";
    }

    public static function print_page_url(){
        echo "<pre>\n";

        $request_url = nomad::schemeHostPort().$_SERVER['REQUEST_URI'];
        $redirect_url = nomad::schemeHostPort().@$_SERVER['REDIRECT_URL'];
        $referrer_url = @$_SERVER['HTTP_REFERER'];
        #echo '<font color="grey">'.$referrer_url.'</font>'."\n";
        #echo '<font color="grey">'.$redirect_url.'</font>'."\n";
        echo 'requested: <font color="grey">'.$request_url.'</font>'."\n";

        $shp = nomad::schemeHostPort();      $shp = '<font color="grey">'.$shp.'</font>';
        $site = nomad::sitePath();           $site = '<font color="red">'.$site.'</font>';
        $dirs = nomad::$pathComponentDirs;   $dirs = '<font color="darkorange">'.$dirs.'</font>';
        $slsh = '/';                        $slsh = '<font color="green">'.$slsh.'</font>';
        $page = nomad::pageFilename();  $page = '<font color="blue">'.$page.'</font>';
        $ext =
        $all = "$shp|$site|$dirs|$slsh|$page";
        $all = str_replace('|','', $all);
        echo "resource:  <b>$all</b>\n";
        echo "</pre>\n";
    }
}