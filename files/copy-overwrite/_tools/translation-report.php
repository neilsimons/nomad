<html><body>
<style type="text/css">
    td, tr{ white-space: nowrap; font-family: monospace; }
    .warning{ color:red; }
    tr:nth-child(odd){ background-color:#f0f0f0; }
</style>
<?php

$sitedir = dirname(dirname($_SERVER['SCRIPT_FILENAME']));
$results = unserialize( file_get_contents( "$sitedir/_translation/results.phpser"));
$options = unserialize( file_get_contents( "$sitedir/_translation/options.phpser"));




// print links to pages
echo "<table><tr><th>no</th><th>original</th><th>translated</th><th>notices</th><th>warnings</th><th>notes</th></tr>\n";
print_translation_links_rows('.shtml');
print_translation_links_rows('.php');
print_translation_links_rows('.html');
echo "</table>\n";

// print hrefs info
#echo "<table>\n";
#print_hrefs

// cross reference hrefs with pages
echo "<pre>\n";
$pages = [];
foreach($results['translated_files'] as $orgrelpath => $ignore){
    $pages['/'.$orgrelpath] = 0;
}


$hrefs = [];
// redundant domain components to remove from hrefs (will have been done in translated versions)
$domain = $options['siteDomain'];
$deletes = [
    "https://www.$domain",
    "https://$domain",
    "http://www.$domain",
    "http://$domain",
    "//www.$domain",
    "//$domain",
];
foreach($results['hrefs'] as $href => $sourcePagePath){
    #if(strpos($href,'http')!==false) continue;
    if(strpos($href,'.shtml')!==false){
        // remove redundant domain
        $href = str_replace($deletes,'',$href);
        $href = str_replace('%20',' ', $href);
        // remove fragmnets
        if(strpos($href,'#')!==false){
            $pos = strpos($href,'#');
            $href = substr($href,0,$pos+0);
        }

        $href2 = href2path($href, $sourcePagePath);
        #echo "$href2 ($sourcePagePath $href )\n";
        $hrefs[$href2] = 0;
    }
}

// cross reference
foreach($pages as $pagepath => $hrefcount){
    foreach($hrefs as $hrefpath => $pagecount){
        if($hrefpath === $pagepath){
            $hrefs[$hrefpath]++;
            $pages[$pagepath]++;
        }
    }
}
// results
echo "pages not linked to\n";
foreach($pages as $pagepath => $hrefcount){
    if(!$hrefcount) echo "$hrefcount $pagepath\n";
}
echo "hrefs without pages\n";
foreach($hrefs as $hrefpath => $pagecount){
    if(!$pagecount) echo "$pagecount $hrefpath\n";
}
echo "</pre>\n";



function print_translation_links_rows($fileext){
    global $results, $options;
    $n=0;
    echo "<tr><th colspan=0>$fileext</th></tr>\n";
    foreach($results['translated_files'] as $orgrelpath => $a){
        $dstrelpath  = $a['dstrelpath'];
        $notes      = (array) @$a['notes'];
        $notices    = (array) @$a['notices'];
        $warnings   = (array) @$a['warnings'];
        if(substr($orgrelpath,-strlen($fileext))===$fileext){
            $n++;
            echo "<tr><td>$n</td>";

            // link to original source
            $href = 'https://'.$options['siteDomain'].'/'.$orgrelpath;
            printf('<td><a href="%s">%s</a></td>', $href, $href);

            // link to new
            $href = "../$dstrelpath";
            printf('<td><a href="%s">%s</a></td>', $href, $href);


            // notices
            echo "<td>";
            // classles - (empty class) print the messages
            foreach($notices as $class => $msgs){
                if($class!=='') continue;
                // no class - print the messages
                foreach($msgs as $msg){
                    echo "$msg ";
                }
            }
            // with class - join by class and make detail available in title (mouseover)
            foreach($notices as $class => $msgs){
                if($class==='') continue;
                $detail = join(", ", $msgs);
                $count = count($msgs);
                echo "<span class=\"notice\" title=\"$detail\">$class($count)</span>";
            }
            echo "</td>";


            // warnings
            echo "<td>";
            foreach($warnings as $class => $msgs){
                $detail = join(", ",$msgs); $count = count($msgs);
                echo "<span class=\"notice\" title=\"$detail\">$class($count)</span>";
            }
            echo "</td>";

            // notes
            #echo "<td>";
            #foreach($notes as $note){
            #    echo "$note ";
            #}
            #echo "</td>";


            echo "</tr>\n";
        }
    }
}

?>
</body></html>
<?php

function href2path($hrefpath, $pagepath){
        // ensure /pagepath starts with /
        if(substr($pagepath,0,1)!=='/') $pagepath = "/$pagepath";

        // if href starts with / is absolute path so cant be further resolved
        if(substr($hrefpath,0,1)==='/') return $hrefpath;

        // therefore is relative path which potentially requires resolving

        // create array containing components making up pages directory path
        $pagepath = trim($pagepath,'/'); // trim outer / (will readd later)
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