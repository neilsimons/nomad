<?php

?>
<nav class="navbar navbar-fixed ">
    <a class="sr-only sr-only-focusable" href="#main">
        skip to content
    </a>
    <div>
        <div class="clearfix">
            <div id="navbar">
                <? include nomad::includesDir().'/tool-menu.php' ?>
            </div>
        </div>
        <div id="search-panel" class="collapse">
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



<div id="header" class="">
    <h1>vanilla nomad template</h1>
    <!-- logo etc here -->
</div>

<div id="nav" class="no-js">
    <?=nomad::getMainMenuHtml() ?>
</div>

<div id="middle-and-footer">
    <div id="middle" class="middle container no-js">
