<?php

#include '../../';


class HrefTest extends \PHPUnit_Framework_TestCase
{
    function test_dev_hrefs(){
        #$this->assertSame(1,1);

        $r1 = devHrefs::href2path('other.html','/products/forks.html');

        $r2 = devHrefs::href2path('/knives.html','/products/forks.html');

        $r3 = devHrefs::href2path('../index.html','/products/extra/page.html');

        $r4 = devHrefs::href2path('publications/annals.shtml','/publications.shtml');

        $this->assertSame('/products/other.html', $r1);
        $this->assertSame('/knives.html', $r2);
        $this->assertSame('/products/index.html', $r3);
        $this->assertSame('/publications/annals.shtml', $r4);

        $r = devHrefs::href2path('./annals.shtml','/publications.shtml'); $this->assertSame('/annals.shtml', $r);
        $r = devHrefs::href2path('index.shtml','/dir/page.shtml'); $this->assertSame('/dir/index.shtml', $r);

        // publications/asaonline/asaonline_eds.shtml ../../publications/asaonline/asaonline_articles.shtml
        $r = devHrefs::href2path('../../publications/asaonline/asaonline_articles.shtml','publications/asaonline/asaonline_eds.shtml');
        $this->assertSame('/publications/asaonline/asaonline_articles.shtml', $r);

    }

}


class devHrefs{

    /**
     * resolves href to an absolute path given the originiating page
     * @param $href
     * @param $pagepath
     * @return string
     */
    static function href2path($hrefpath, $pagepath){
        // ensure /pagepath starts with / (is absoulte path)
        if(substr($pagepath,0,1)!=='/') $pagepath = "/$pagepath";

        // if href starts with / is absolute path so cant be further resolved
        if(substr($hrefpath,0,1)==='/') return $hrefpath;

        // else is relative path which potentially requires resolving

        // create array containing components making up pages directory path
        $pagepath = trim($pagepath,'/'); // trim all outside / (will readd later)
        $pagedirpath = (strpos($pagepath,'/')!==false) ? dirname($pagepath) : '' ;
        $pagedirparts = (strlen($pagedirpath)) ? explode('/',$pagedirpath) : [];
        #echo "pagedirparts=(".join(",",$pagedirparts).")".count($pagedirparts)." \n";

        // same with href
        $hrefparts = explode('/', $hrefpath);

        foreach ($hrefparts as $hrefpart) {
            switch(true){
                case($hrefpart === '..'): array_pop($pagedirparts); break; // backup
                case($hrefpart === '.'): break; // stay put
                default: $pagedirparts[] = $hrefpart; // move into
            }
            #echo "hrefpart='$hrefpart' \$pagedirparts(".join(',', $pagedirparts).")\n";
        }
        return '/'.join('/',$pagedirparts);
    }
}

function absurl($url) {
    global $pgurl;
    if(strpos($url,'://')) return $url; //already absolute
    if(substr($url,0,2)=='//') return 'http:'.$url; //shorthand scheme
    if($url[0]=='/') return parse_url($pgurl,PHP_URL_SCHEME).'://'.parse_url($pgurl,PHP_URL_HOST).$url; //just add domain
    if(strpos($pgurl,'/',9)===false) $pgurl .= '/'; //add slash to domain if needed
    return substr($pgurl,0,strrpos($pgurl,'/')+1).$url; //for relative links, gets current directory and appends new filename
}

function nodots($path) { // Resolve dot dot slashes, no regex!
    $arr1 = explode('/',$path);
    $arr2 = array();
    foreach($arr1 as $seg) {
        switch($seg) {
            case '.':
                break;
            case '..':
                array_pop($arr2);
                break;
            case '...':
                array_pop($arr2); array_pop($arr2);
                break;
            case '....':
                array_pop($arr2); array_pop($arr2); array_pop($arr2);
                break;
            case '.....':
                array_pop($arr2); array_pop($arr2); array_pop($arr2); array_pop($arr2);
                break;
            default:
                $arr2[] = $seg;
        }
    }
    return implode('/',$arr2);
}