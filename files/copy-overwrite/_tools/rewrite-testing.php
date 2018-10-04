<?php
echo "<pre>\n";


echo "\$_REQUEST => [\n";
foreach(@$_REQUEST as $k => $v){
    echo "'$k' => ".htmlentities(var_export($v,1))."\n";
}
echo "];\n";


/*
$links = [
    '//nomad.app2.lan/rewrite.php',
    '//nomad.app2.lan/nonexistant/path',
    '//nomadit.app2.lan/nomad/nonexistant/path',
];
foreach($links as $link){
    echo sprintf('<a href="%s">%s</a>'."\n",
        $link, $link);
}
*/

echo "</pre>\n";