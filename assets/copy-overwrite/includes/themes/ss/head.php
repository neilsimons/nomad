<?php
$sitepath    = nomad::sitePath();
$theme       = nomad::theme();
$pagestyle   = nomad::getPageStyle();

// todo use resourcePath() everywhere
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<!-- icons -->
<link rel="icon" href="<?=nomad::resourcePath("/favicon.ico")?>"/>
<link rel="apple-touch-icon" href="<?=nomad::resourcePath("/res-nomad/shared/images/nomad-icon-114.png")?>"/>
<link rel="apple-touch-icon" href="<?=nomad::resourcePath("/res-nomad/shared/images/nomad-icon-72.png")?>" sizes="72x72" />
<link rel="apple-touch-icon" href="<?=nomad::resourcePath("/res-nomad/shared/images/nomad-icon-114.png")?>" sizes="114x114" />

<!-- css -->
<link href="<?=nomad::versionedResourcePath("/res-nomad/shared/css/strap.css")?>" rel="stylesheet" type="text/css" title="css"/>
<link href="<?=nomad::versionedResourcePath("/res-nomad/$theme/css/site.css")?>" rel="stylesheet" type="text/css" title="css"/>
<link href="<?=nomad::versionedResourcePath("/res-nomad/$theme/css/all.css")?>" rel="stylesheet" type="text/css" title="css"/>
<link href="<?=nomad::versionedResourcePath("/res-custom/$theme/css/fixups.css")?>" rel="stylesheet" type="text/css" title="css"/>
<link href="<?=nomad::versionedResourcePath("/res-custom/$theme/css/$pagestyle.css")?>" rel="stylesheet" type="text/css" title="css"/>
<!-- css extras : fonts etc -->
<!--<link href="//fonts.googleapis.com/css?family=Lusitana" rel="stylesheet">-->
<!-- javascript -->
<? if ( $google_analytics ): ?>
    <script type="text/javascript" src="<?="$sitepath/res-nomad/$theme/js/ga.js" ?>"></script>
<? endif ?>
<? if ( ! $page_is_findable ): ?>
    <meta name="robots" content="nofollow"/>
    <meta name="robots" content="noindex"/>
<? endif ?>
<script type="text/javascript" src="<?="$sitepath/res-nomad/$theme/js/jquery-3.1.1.min.js" ?>"></script>
<script type="text/javascript"> jQuery.noConflict() </script>
<? if ( $google_site_search ): // add the site search and other external js?>
    <script type="text/javascript" src="<?="$sitepath/res-nomad/$theme/js/gcs.js" ?>"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script type="text/javascript" src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<? endif ?>
<script src="<?=nomad::sitePath()."/res-nomad/$theme/js/but-col-drop-tab-tran.js"?>"></script>
<?=nomad::getPageStylesJavascript() ?>
<script type="text/javascript" src="<?="$sitepath/res-nomad/$theme/js/site-functions.js"?>"></script>
<script type="text/javascript" src="<?="$sitepath/res-nomad/$theme/js/style-changer.js"?>"></script>
<script> // jQuery(document).ready(function($){try{top.setPageLoaded(window,'<?=nomad::pagePath('')?>');}catch(e){ }}); // to do - frameset based previous site comparison tool </script>

