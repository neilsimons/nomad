<?php
// include at start of {mypage}.phtml files - not includes
// MUST reside in [site-base-dir/]includes    eg /server/webroot [/site-subdir] /includes/setup.php

#require_once __DIR__.'/lib/nomad/nomad.php';  // include nomad classes & functions
$loader = include __DIR__.'/vendor/autoload.php';  // use composer autoloader

nomad::initVars( dirname(__DIR__) ); // supply the site root dir
nomad::setupErrorHandling();

require_once __DIR__ . '/site-options.php'; // load configuration




// debugging stuff - here rather than in site-options.php to keep it clean

register_shutdown_function(function(){
    $output = ob_get_clean();
    // conditionaly print debug messages before page
    if($_SERVER['REMOTE_ADDR']=='192.168.111.1') print_dmsgs([@$_REQUEST['dmsg'],@$_COOKIE['dmsg']]);
    /* example search & replace
    $NomadLinkFmt = '<a href="https://nomadit.co.uk/">%s</a>';
    $output = str_replace(
        ['Home',        'Nomad'],
        ['Home Page',   sprintf($NomadLinkFmt,'Nomad')],
        $output
    );*/
    echo $output;
});
