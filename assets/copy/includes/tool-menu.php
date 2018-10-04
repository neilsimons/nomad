<ul>
    <li class="dropdown">
        <a id="menu-toggle" class="dropdown-toggle" href="#nav"
           data-toggle="collapse" data-target="#nav"
           aria-haspopup="true" aria-expanded="false" aria-controls="nav"
           title="toggle main menu">
            <span class="glyphicon glyphicon-menu-hamburger"></span>
            <span> Main&nbsp;menu </span>
            <span class="sr-only">Toggle main menu</span>
        </a>
    </li>
</ul>



<ul class="pull-right">
    <li><a href="https://www.facebook.com/xxxxxxxxxxxxxxxxxxxxxx">
        <span class="icon-facebook"></span>
        <span> Facebook </span>
    </a></li>

    <li><a href="https://twitter.com/hashtag/xxxxxxxxxxxxxxxxx?f=tweets&vertical=default" target="_blank">
        <span class="icon-twitter white-bird"></span>
        <span> Twitter </span>
    </a></li>

    <li class="dropdown">
        <a id="site-tool" href="#" class="dropdown-toggle" data-toggle="dropdown"
           role="button" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-info-sign"></span>
            <span> Site </span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a class="style-toggle-btn" href="#"
                   title="Change page colours">
                <span class="glyphicon glyphicon-adjust"></span>
                <span> Contrast </span>
            </a></li>
            <li><a href="<?=nomad::menuLink("/access")?>" accesskey="0" title="Access keys etc">
                <span class="glyphicon glyphicon-headphones"></span>
                <span> Accessibility </span>
            </a></li>
            <li><a href="<?=nomad::menuLink("/privacy-policy")?>">
                <span class="glyphicon glyphicon-check"></span>
                <span> Privacy policy </span>
            </a></li>
            <li><a href="<?=nomad::menuLink("/site-map")?>">
                <span class="glyphicon glyphicon-th-list"></span>
                <span> Site map </span>
            </a></li>
        </ul>
    </li>

    <li><a href="<?=nomad::menuLink("/timetable")?>" accesskey="8">
        <span class="glyphicon glyphicon-calendar"></span>
        <span> Calendar </span>
        </a></li>

    <li><a href="https://cocoa.nomadit.co.uk/cocoa.php5?ConferenceID=99999999999999" accesskey="3">
        <span class="glyphicon glyphicon-user"></span>
        <span> Login </span>
        </a></li>

    <li><a id="search-toggle" href="#" data-toggle="collapse"
           data-target="#search-panel" aria-haspopup="true" aria-expanded="false">
        <span class="glyphicon glyphicon-search"></span>
        <span> Search </span>
        <span class="caret"></span>
        </a></li>
</ul>
