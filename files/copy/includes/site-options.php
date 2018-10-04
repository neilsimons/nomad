<?php

date_default_timezone_set( 'Europe/London' );

nomad::setPreferredPageExt(''); // emptystring = no ext, eg when we utilise rewrite of /path  -> /path.phtml
nomad::setPageStyles(['style-default','style-dark']); // default first
nomad::setTheme('asa'); // which set of php includes and associated resources (css/js/images) to load
$google_analytics   = 0; // Google tracking, set up in ecma-scripts/ga.js when set to true
$google_site_search = 0; // Google custom site search using ecma-scripts/gcs.js when true
$page_is_findable   = 0; // sets 'no index, no follow' when false
#$pagestyle = nomad::getPageStyle();
#$style_cookie_name  = 'style';
#$fb_app_id          = '184256358.....';

/* example menu array
nomad::setMenuArray([
    'Home' => '/', // normal use where /index.phtml is home page
    #'Home' => '/home', // allows separate /home to be used as home page where /index.phtml is a one-off landing page, ie shiftingstates.info
    'Programme'       => [
        'Theme'    => "/theme",
        'Keynotes' => '/keynotes',
        'Events' => '/events',
        'Panels' => 'http://nomadit.co.uk/shiftingstates/conferencesuite.php/panels/Views/allpanels',
        'Labs' => '/labs',
        'Timetable' => '/timetable',
    ],
    'Registration'    => "/registration",
    'Practical Information' => [
        'Information for convenors/authors' => "/practical/info",
        'Accommodation'                          => "/practical/accommodation",
        'Food and drink'                          => "/practical/food",
        'Map'                                    => "/practical/map",
//		'Childcare'                              => "/practical/childcare",
        'Visas'                                 => "/practical/visas",
        'Travel'                                 => "/practical/travel",
    ],
    'Book exhibit'    => "/exhibit",
    'Funding'    => "/funding",
]);
*/
