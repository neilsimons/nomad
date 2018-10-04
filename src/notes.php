<?php
/*
this is currently being developed as a package located in /app/lbdev/nomad




[ideas for res layout]
in
/res-nomad/THEME/css/sheet.css

theme specific
/res-nomad/THEME/images/image.png    src='../images/image.png'

common to all
/res-nomad/shared/images/image.png   src='../../shared/images/image.png'
/res-nomad/shared/fonts/font.ttf     src='../../shared/fonts/font.tff'



todo: split out themes as separate packages, requiring shared resources (all on public github repos)
[aims]
content maintained separately
want to maintain theme + configuration of theme (menus etc) - just not content!
this should include site-options.php, .htaccess should be part of this also (wont affect cs styling - just host site)
perhaps call something other than theme?
-not required - the conf bits incldued are for the rendering of the theme (eg menuLinks), so no distinction neccessary
todo tool-menu.php should be in incldues/themes/
todo: nomad::getIncludesDir() returns "includes/themes/{THEME}" - then have complete *theme* separation!
- in future, use nomad::getIncludesDir(true)?
[how]

[what]                          [package]
siteroot
  .htaccess                     nomad
  favico*                       theme-default
  _tools/                       nomad
    some-script.php
  includes/                     nomad
    composer.json.suggested?
    head.php foot.php etc..
    tool-menu.php
    site-options.php
    setup.php
    themes/*                    nomad-theme-XXX


nomadit/nomad                   php lib, including package install code, basic files / dirs
nomadit/nomad-theme-shared01    is depended upon by all nomad-theme-XXXXX themes
nomadit/nomad-theme-theme01     is the default theme, perhaps always required for purpose of initial checking on install
nomadit/nomad-theme-asa2018     example client theme (asa2018)

### git repo of content? - all files excluding /includes/* /res_nomad/* /res_custom/*
but perhaps with includes/composer.json|lock so can run composer install ?? how does this work???

theme files:
    /includes/themes/THEME/* (including conference-suite config php file)
                              - so nomadit.co.uk /shared-style-blah/_tools/update-style.php knows where to find host site
    /res-nomad/THEME/*      (including conference-suite css files - cs-xxxx.css )
    /res-custom/THEME/*     (including conference-suite css files - cs-xxxx.css )




steps
1) change nomadit/nomad -> github (restart git repo)
2) create nomadit/nomad-theme-shared01 -> github



siteroot
    includes
        (project root)
        vendor...
        site-options.php
        setup.php
        themes
            {THEME}
    res-custom
        {THEME}
    res-nomad
        {THEME}
    assets (new)
        {THEME}
            css
                custom.css



 */

