<?php


trait nomad_templateFunctions
{

    protected static function getMenuArray()
    {
        #global $menu_array;
        #return (array) $menu_array;
        return self::$menuArray; // todo remove this function and those that use it call the var directly
    }



    /**
     * Get the page style option from the style cookie, changes here must also be changed in style-changer.js
     * @return string
     */
    public static function getPageStyle()
    {
        return ( isset( $_COOKIE['styleDefCon'] ) && in_array( $_COOKIE['styleDefCon'], self::$pageStyles ) )
            ? $_COOKIE['styleDefCon']
            : self::$pageStyles[0];
    }

    /**
     * returned javascript with array of allowed styles for style-changer.js
     * @return string
     */
    public static function getPageStylesJavascript()
    {
        return '<script type="text/javascript">var site_styles=["' . implode( '","', self::$pageStyles ) . '"]</script>' . "\n";
    }


    /**
     * returns html breadcrumb trail eg  home > dir1 > dir2 > page
     * check first if page is in menu array, use that traversal to generate the crumbs
     * else add the current pages path components
     *
     * @return string
     */
    public static function getCrumbsHtml()
    {
        $html = "\n";
        $glue  = ' <span class="glyphicon glyphicon-chevron-right"></span> ';
        $trail = [];
        $menuArray = self::getMenuArray();

        // get pagePath (only from site-root), and adjust to the same format as how the $menu_array paths are defined
        $pagePath  = self::pagePathFromSiteRoot('');  // '' = no extension as per $menu_array path definitions
        if(substr($pagePath,-5)=='index') $pagePath = substr($pagePath,0,strlen($pagePath)-5); // also remove /path/"index" as per $menu_array path definitions
        #dmsg('pagePath',$pagePath,'crumbs');

        // prepare home link for beginning of trail
        $homeLink = '<a href="' . self::_menuLink2href(self::homePath()) . '">Home</a>';

        // search the menu array for matching path
        if ( self::findPageInMenu( $pagePath, $menuArray, $trail ) ) {
            $trail[] = $homeLink;
            dmsg('trail',$trail,'crumbs');
            $html .= implode( $glue, array_reverse( $trail ) );
        } else {
            // not in menuArray, use this pages dir components to build a virtual trail
            $trail = explode('/', trim(self::$pathComponentDirs,'/'));      if(isset($trail[0]) && $trail[0]=='') $trail=[];
            #$trail[] = '<a href="' . self::_menuLink2href($pagePath) . '">'.self::pageFilename('').'</a>';
            $trail[] = self::pageFilename(''); // dont href self
            array_unshift($trail, $homeLink);
            dmsg('trail',$trail,'crumbs');
            $html .= '<span class="capitalize">' . implode( $glue, $trail ) . '</span>';
        }
        return $html;
    }



    /**
     * used by getCrumbsHtml, self
     * given $pagePath, find in menuArray adding to trail
     *
     * @param string $pagePath
     * @param array $menuArray
     * @param array $trail      hyperlinks/labels
     *
     * @return bool true if found
     */
    protected static function findPageInMenu($pagePath, $menuArray, &$trail )
    {
        dmsg('pagePath',$pagePath,'crumbs');
        foreach ( $menuArray as $key => $item ) {
            // determine if item is a link (path to resource)
            $link = false;
            if(is_string($item)) { // string item is a link
                $link = $item;
            } elseif(is_array($item)){
                // 1st unkeyed item is path to index style resource
                if(isset($item[0]) && is_string($item[0])){
                    $link = $item[0];
                }
                // lets also assume that the first keyed item represents the index style resource
                $first_val = reset($item);
                $first_key = key($item);
                if(isset($first_key)){
                    $link = $item[ $first_key ];
                    // keep the original key (describes the whole array of links)
                    #$key = $first_key;
                }
            }

            dmsg('link',$link,'crumbs');

            // if link matches current page-path, add it to trail and return true - found
            if ($link && ($pagePath == $link)){
                $is_home = ($link==self::homePath()); // dont add home link to trail (getCrumbsHtml adds it to all trails)
                if(!$is_home) $trail[] = $key;
                return true;
                // a hyperlink is redundant (links to the current page) - but was requested and maybe looks more complete
                #if(!$is_home) $trail[] = '<a href="' . self::_menuLink2href($link) . '"> ' . $key . ' </a>';
                #return true;
            }

            // recurse into tree
            if ( is_array( $item ) && self::findPageInMenu( $pagePath, $item, $trail ) ) {
                // found in $item (subarray)
                #dmsg('$link',$link,'crumbs');
                #dmsg('$key',$key,'crumbs');
                $trail[] = ($link) ? '<a href="' . self::_menuLink2href($link) . '"> ' . $key . ' </a>' : $key;
                #$trail[] = '<a href="' . self::_menuLink2href($link) . '"> ' . $key . ' </a>';
                return true;
            }
        }
        return false;
    }




    /**
     * @return string
     */
    public static function getMainMenuHtml()
    {
        $menu        = self::getMenuArray();
        #$sitePath    = self::sitePath();
        // no need to add /home link - can be implemented by defining in $menu_array and setting nomad::setPreferredExt()
        $html = '<ul id="main-menu" role="menu" class="nav">'.self::getMenuItemsHtml($menu) . "\n" . '</ul>' . "\n";
        return $html;
    }




    /**
     * recursive function to render menu items as nested lists
     * @param array $menu
     * @param string $indent
     * @return string
     */
    protected static function getMenuItemsHtml($menu, $indent='' )
    {
        $html = '';
        foreach ( $menu as $key => $item ) {
            $link = '#';
            if ( is_string( $item ) ) {
                $link = $item;
                dmsg('$link-1', $link, 'menu');
            } elseif ( ( is_array( $item ) && isset( $item[0] ) && is_string( $item[0] ) ) ) {
                $link = $item[0];
                dmsg('$link-2', $link, 'menu');
            }

            if ( is_array( $item ) ) {
                $html .= '
' . $indent . '  <li>
' . $indent . '    <a href="' . self::_menuLink2href($link) . '"> ' . $key . ' <span class="caret"></span> </a>
' . $indent . '    <ul class="nav nav-sidebar collapse">'
                    . self::getMenuItemsHtml( $item, $indent . '    ' ) . '
' . $indent . '    </ul>
' . $indent . '  </li>';
            } elseif ($key!==0) {
                $link = $item;
                #$class = ($link===self::pagePath('')) ? 'class="active"' : ''; // done in javascript
                #dmsg('$link($path.$item)+ext', $link, 'menu');
                $html .= '
' . $indent . '  <li><a href="' . self::_menuLink2href($link) . '"> ' . $key . ' </a></li>';
            }
        }

        return $html;
    }

    /**
     * links in menu array are either internal (extensionless) paths or external urls
     * if internal path, prepend sitepath and append preffered extention
     *
     * used by getCrumbsHtml getMenuItemsHtml findPageInMenu
     *
     * @param string $linkFromMenu   eg  "/", "/members/john/bio"
     * @return string
     */
    protected static function _menuLink2href($linkFromMenu){
        $prefix   = self::sitePath();
        // if set used forced prefix eg "https://some.other.domain/some/other/path" - for generating menu markup for conference suite
        if(isset(self::$menuLinkPrefixOverride)) $prefix = self::$menuLinkPrefixOverride; // can be empty string!

        switch(true){
            // external link or fragment (no change)
            case($linkFromMenu==='#'):                          // no change
            case(strpos($linkFromMenu, 'http')===0):    // an external link (^http...) - no change
            case(strpos($linkFromMenu, '//')===0):      // an external link (^//domain...) - no change
                                                            $href = $linkFromMenu;                                  break;
            // internal link - no extension
            case($linkFromMenu==='/'):                           // if  "/" (eg for /index.phtml) - cant have extension
            case(substr($linkFromMenu,-1,1)==='/'): // ends in / eg "dir/" (for dir/index.phtml) - cant have extension
                                                            $href = $prefix . $linkFromMenu;            break;
            // interrnal link
            default:                                        $href = $prefix . $linkFromMenu . self::$preferredPageExt;
        }
        return $href;
    }

    // public wrapper for above - can be used in tool-menu.php
    public static function menuLink($link){
        // _menuLink2href auto adds file extentions for internal links - perhaps check here if has been accidentally specified and throw?
        return self::_menuLink2href($link);
    }



}