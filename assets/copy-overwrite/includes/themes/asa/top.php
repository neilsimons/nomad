<?php
$sitepath    = nomad::sitePath();
$theme       = nomad::theme();

// aspect ration of "banner-aspect-..." transparent image defines the layout
// the first empty div (eupopup..) just triggers the eupopup plugin via eupopup class (has no affect on markup generation)
?>
<script>
jQuery(document).ready(function() {
    jQuery(document).euCookieLawPopup().init({
        cookiePolicyUrl : '<?=nomad::menuLink('/privacy')?>',
        popupPosition : 'bottom',
        colorStyle : 'default',
        compactStyle : false,
        popupTitle : '',
        popupText : 'This site uses cookies. For more information about cookies and how we handle user data please consult our privacy policy.',
        buttonContinueTitle : 'Continue',
        buttonLearnmoreTitle : 'Find&nbsp;out&nbsp;more.',
        buttonLearnmoreOpenInNewWindow : false,
        agreementExpiresInDays : 3000,
        autoAcceptCookiePolicy : false,
        htmlMarkup : null
    });
});
</script>
<div id="banner" class="">
    <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/banner-aspect-50x9.png" alt=""/>
    <!--<div id="banner-credit">
         banner credit text
    </div>-->
</div>
<div id="banner-overlay">
    <div id="header-right">
        <!--
        <a class="light" target="_blank" href="http://associated-organisation.org/" title="associated organisation">
            <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/logo-example-associated-organisation.png" alt="associated organisation" />
        </a>
        <a class="beige" target="_blank" href="http://associated-organisation.org/" title="associated organisation">
            <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/logo-example-associated-organisation.png" alt="associated organisation" />
        </a>
        <a class="light" target="_blank" href="http://associated-organisation.org/" title="associated organisation">
            <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/logo-example-associated-organisation.png" alt="associated organisation" />
        </a>
        -->
    </div>
</div>


<div id="nav-spacer">
    <nav class="navbar navbar-fixed stickable">
        <a class="sr-only sr-only-focusable" href="#main">
            skip to content
        </a>
        <div>
            <div class="clearfix">
                <div id="navbar">
                    <!--<span id="sticky-logo">
                        <a href="<?=$sitepath?>/" title="example conference">Site/Conf Name</a>
                    </span>-->
                    <? include nomad::includesDir().'/tool-menu.php' ?>
                </div>
            </div>
            <div id="search-panel" class="middle collapse">
                <a href="#" data-toggle="collapse"
                   data-target="#search-panel" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
                <?php if ( $google_site_search ): ?>
                    <!-- Google custom site Search -->
                    <div class="gcse-search"></div>
                <?php else: ?>
                    (Google site search is disabled on this page)
                <?php endif ?>
            </div>
        </div>
    </nav>
</div>


<div id="nav-scrollintoview-target"></div>
<div id="nav" class="no-js">
        <?=nomad::getMainMenuHtml() ?>
</div>


<div id="middle-and-footer">
    <div id="middle" class="middle container no-js">
