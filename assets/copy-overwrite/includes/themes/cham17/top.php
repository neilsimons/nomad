<?php
$sitepath    = nomad::sitePath();
$theme       = nomad::theme();
?>
<div id="banner" class="stickable">
    <img src="<?=$sitepath?>/" alt=""/>

    <div id="banner-credit">
		credit
	</div>
</div>

<div id="logo-text" class="middle stickable">
    <a class="sr-only sr-only-focusable" href="#main">
        skip to content
    </a><br />
</div>
<div id="top-container" class="no-js">
    <div id="header">
        <div id="banner-window">
            <a href="<?=$sitepath?>/index" tabindex="-1">
                <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/banner.jpg" title="home" alt=""/>
            </a>
        </div>
        <div id="header-right">
            <a class="light" target="_blank" href="http://associated-organisation.org/" title="associated organisation">
                <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/logo-example-associated-organisation.png" alt="associated organisation" />
            </a>
            <a class="beige" target="_blank" href="http://associated-organisation.org/" title="associated organisation">
                <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/logo-example-associated-organisation.png" alt="associated organisation" />
            </a>
            <a class="light" target="_blank" href="http://associated-organisation.org/" title="associated organisation">
                <img src="<?=$sitepath?>/res-custom/<?=$theme?>/images/logo-example-associated-organisation.png" alt="associated organisation" />
            </a>
        </div>
    </div>
</div>
<div id="spacer">
    <nav class="navbar navbar-fixed stickable">
        <div>
            <div class="clearfix">
                <div id="navbar">
					<span id="sticky-logo">
						<a href="<?=$sitepath?>/" title="example conference">Site/Conf Name</a>
					</span>
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
<div id="middle-and-footer">
    <div id="middle" class="middle container no-js">
        <div id="above">
            <div id="nav"><?=nomad::getMainMenuHtml() ?></div>
        </div>
