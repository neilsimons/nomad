jQuery(document).ready(function ($) {
    $('.no-js').removeClass("no-js");

    if ($('#right').length > 0) {
        $('#main').addClass('has-right');
    }

    $('#menu-toggle').on('click', function (e) {
        // _ns - the target (to scroll into view) if sticky toolbar, will be hidden underneath toolbar
        // perhaps target an invisible item above it?
        //var $target = $('#nav > ul ');
        var $target = $('#nav-scrollintoview-target');

        e.preventDefault();
        //TO DO:Focus on first item;
        if (!$('#left').hasClass('in')) {
            //expanding
            window.setTimeout(
                function () {
                    scrollIntoView($target);
                },
                350 // just longer than the menu takes to expand!
            );
        } else {
            if (!targetInView($target)) {
                e.stopPropagation();
                scrollIntoView($target);
            }
        }
    });

    $('a[fragment-link=true]').on('click', function (event) {
        event.preventDefault();
        var button = $(event.target);
        var selector = button.attr('data-target');
        console.log(selector);
        // ensure parent panel is uncollapsed
        var parent = $(selector).parent(); //console.log(parent);
        parent.collapse('show');
        // scroll to the element
        window.setTimeout(
            function () {
                scrollIntoView($(selector));
                //$('body').trigger(event);
            }, 350
        );
    });

    $('a[aria-haspopup=true]').on('click', function (event) {
        event.preventDefault();
        //TO DO:Focus on first item;
        var button = $(event.target);
        var selector = button.attr('data-target');
        if (selector == '#search-panel')
            return;
//        if (!$(selector).hasClass('in')) {
        if (button.attr('aria-expanded')=='false') {
            //expanding
            console.log('expanding..');
            window.setTimeout(
                function () {
                    setButtonToCollapse(button);
                    scrollIntoView($(selector));
                },
                300
            );
        } else {
            //collapsing
            console.log('collapsing..');
            window.setTimeout(
                function () {
                    setButtonToExpand(button);
                },
                300
            );
        }
    });

    var navMenu=document.getElementById('nav');
    $(window).resize(function() {
        if($(window).width()>1100 && navMenu.style && navMenu.style.height && navMenu.style.height=='0px') {
             navMenu.removeAttribute("style");
         }
    });

    $('#navbar a').each(function () {
        if (!$(this).attr('title')) {
            $(this).attr('title', $(this).find('span:nth-of-type(2)').html());
        }
    });

    function setButtonToCollapse(button) {
        var html = button.html();
        html = html.replace('more', 'less');
        html = html.replace('More', 'Less');
        html = html.replace('glyphicon-plus', 'glyphicon-minus');
        button.html(html);
    }

    function setButtonToExpand(button) {
        var html = button.html();
        html = html.replace('less', 'more');
        html = html.replace('Less', 'More');
        html = html.replace('glyphicon-minus', 'glyphicon-plus');
        button.html(html);
    }





    function targetInView($target) {
        //console.log('targetInView');
        var scroll = $(window).scrollTop();
        return ($target.offset().top > scroll )
    }

    function scrollIntoView($target) {

        if(!$target || !$target.offset()) {
            //console.log('cant get offset of scroll target');
            return;
        }
        //console.log('target top = '+$target.offset().top);
        var scroll = $(window).scrollTop();
        //console.log('scroll top = '+scroll);
        if ($target.offset().top < 60) {
            //console.log($target.offset().top+' top near page top - show page top');
            scroll = 0;
        }
        if ($target.offset().top + $target.height() > scroll + $(window).height()) {
            //console.log('bottom below viewport set bottom to bottom');
            scroll = $target.offset().top + $target.height() - $(window).height();
        }
        if ($target.offset().top < scroll) {
            //console.log('top above viewport - show top');
            scroll = $target.offset().top;
        }
        if (scroll < 0) {
            //console.log('top near page top - show page top');
            scroll = 0;
        }

        if (scroll != $(window).scrollTop()){
            //console.log('animate to top');
            $('html, body').animate({
                scrollTop: scroll
            }, 500);
        }
        else {
            //console.log('target in view - no scroll required');
        }

    }


    /* Give "active" class to <a> links to current page, and the <li> that contain them */
    var currentPage = (window.location.pathname);//+window.location.hash;
    $("a").each(function () {
        var href = $(this).attr('href');
        if (href == currentPage
         || href+'/' == currentPage
         || href+'.html' == currentPage
         || href+'.phtml' == currentPage
         || href+'.shtml' == currentPage
    ) { // account for href/ (apache directoryslash), .html, .phtml
            $(this).addClass("active");
            $(this).closest('li').addClass("active");
            var parent = $(this).parent().parent().closest('li');
            parent.addClass("active");
            parent.parent().closest('li').addClass("active");
        }
    });


    var prevScroll = 0;
    var navbar = $('nav.navbar');
    var hideStickyBelowThreshold = $('body').width()*.3 + 200;
    var stickyElements = $('.stickable');
    var watch = $("#banner");
    $(window).scroll(scrollWatch);

    function scrollWatch() {
        var scroll = $(window).scrollTop();
        var goingDown = (prevScroll < scroll);
        prevScroll = scroll;
        var threshold = watch.offset().top + watch.height();

        if (scroll >= threshold) {
            var posY = navbar.offset().top - scroll;
            stickyElements.addClass('sticky');
            $("body").addClass('sticky');
            navbar.offset().top = posY;
        }
        else {
            stickyElements.removeClass('sticky');
            $("body").removeClass('sticky');
        }

        if (navbar.hasClass('sticky')) {
            //return; // dont use lost functionality
            if (scroll >= hideStickyBelowThreshold
                && goingDown
                && !$('#left').hasClass('in')) {
                stickyElements.addClass('lost');
            } else {
                stickyElements.removeClass('lost');
            }
        }
    }



    $("#site-tool").attr('href', '#'); //without javascript this links to the site-map page

    var $collapsedSubmenus = $('body #nav ul ul').addClass("collapse");

    var isSiteMap=$('body').hasClass('site-map');
    $collapsedSubmenus.each(function () {
        var $a = $(this).prev();
        if ($a.attr('href') == '#') {
            $a.addClass('expandButton');
//                $a.append('<span class="glyphicon glyphicon-menu-down"></span>')
        } else if(!isSiteMap){
            var $expandButton = $('<span class="expandButton"><a href="#" class="glyphicon"></a></span>');
            $expandButton.insertBefore($(this));
        }
    });


    if(!isSiteMap){
        var $expandButtons = $("#nav span.expandButton a, #nav a.expandButton");
        $expandButtons.attr('aria-haspopup', true);
        $expandButtons.attr('aria-expanded', false);
        $expandButtons.click(function (event) {
            var searchExpanded=$('#search-toggle').attr('aria-expanded');
            event.preventDefault();
            var $li = $(this).closest('li');
            var $target = $li.find('ul:first');
            if ($target.hasClass('in')) {
    //               Collapse Menu
                $(this).attr('aria-expanded', false);
                $li.parent().find("ul.in").collapse('toggle');
                $li.parent().find("li.in a[aria-expanded=true]").attr('aria-expanded', false);
                $li.parent().find("li.in").removeClass('in');
                $target.collapse('toggle');
                $li.removeClass('in').closest('ul').removeClass('hasOneIn').closest('li').addClass('in');
                $target = $target.parent().parent();
            } else {
    //               Expand Menu
                $(this).attr('aria-expanded', true);
                $li.parent().find("ul.in").collapse('toggle');
                $li.parent().find("li.in a[aria-expanded=true]").attr('aria-expanded', false);
                $li.parent().find("li.in").removeClass('in');
                $target.collapse('toggle');
                $("#nav ul ul.hasOneIn").removeClass('hasOneIn');
                while ($li.length) {
                    $li = $li.addClass('in').closest('ul').addClass('hasOneIn').closest('li');
                }
            }
            $('#search-toggle').attr('aria-expanded',searchExpanded);
            window.setTimeout(
                function () {
                    scrollIntoView($target)
                },
                700
            );
            return false;
        });
    }

});
