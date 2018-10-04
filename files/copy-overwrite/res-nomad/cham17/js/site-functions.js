jQuery(document).ready(function ($) {
    $('#middle').removeClass("no-js");
    $('#top-container').removeClass("no-js");

    $('#search-toggle').on('click', function (e) {
        e.preventDefault();
    });


    if ($('#main').hasClass('full-width')) {
        $('#menu-toggle').addClass('togglable');
        $('#left').addClass('togglable');
    } else if ($('#right').size() == 0) {
        $('#main').addClass('no-right');
    }

    $('#menu-toggle').on('click', function (e) {
        var $target = $('#nav > ul ');
        e.preventDefault();
        //TO DO:Focus on first item;
        if (!$('#left').hasClass('in')) {
            //expanding
            window.setTimeout(
                function () {
                    scrollIntoView($target);
                },
                100
            );
        } else {
            if (!targetInView($target)) {
                e.stopPropagation();
                scrollIntoView($target);
            }
        }
    });

    $('a[aria-haspopup=true]').on('click', function (e) {
        e.preventDefault();
        //TO DO:Focus on first item;
        var button = $(this);
        var selector = button.attr('data-target');
        if (selector = '#search-panel')
            return; // don't scroll into view or amend text on fixed search box
        if (!$(selector).hasClass('in')) {
            //expanding
            window.setTimeout(
                function () {
                    setButtonToCollapse(button);
                    scrollIntoView($(selector));
                },
                700
            );
        } else {
            //collapsing
            window.setTimeout(
                function () {
                    setButtonToExpand(button);
                },
                700
            );
        }
    });

    $('#navbar a').each(function () {
        if (!$(this).attr('title')) {
            $(this).attr('title', $(this).find('span:nth-of-type(2)').html());
        }
    });

    $('#header-right a').each(function () {
        if (!$(this).attr('title')) {
            $(this).attr('title', $(this).find('img').attr('alt'));
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


    var prevScroll = 0;
    var navbar = $('nav.navbar');
    var hideStickyBelowThreshold = $('body').width()*.3 + 200;
    var stickyElements = $('.stickable');
    var watch = $("#header");
    $(window).scroll(scrollWatch);
    function scrollWatch() {
        var scroll = $(window).scrollTop();
        var goingDown = (prevScroll < scroll);
        prevScroll = scroll;
        var threshold = watch.offset().top + watch.height();


        if (scroll >= threshold) {
            var posY = navbar.offset().top - scroll;
            stickyElements.addClass('sticky');
            navbar.offset().top = posY;
        }
        else {
            stickyElements.removeClass('sticky');
        }

        if (navbar.hasClass('sticky')) {
            if (scroll >= hideStickyBelowThreshold
                && goingDown
                && !$('#left').hasClass('in')) {
                stickyElements.addClass('lost');
            } else {
                stickyElements.removeClass('lost');
            }
        }
    }

    function targetInView($target) {
        var scroll = $(window).scrollTop();
        return ($target.offset().top > scroll )
    }

    function scrollIntoView($target) {
        var scroll = $(window).scrollTop();
        if ($target.offset().top + $target.height() < scroll + navbar.height()) {
            //console.log('bottom above viewport - reset top');
            scroll = $target.offset().top - navbar.height()
        }
        if ($target.offset().top + $target.height() > scroll + $(window).height()) {
            //console.log('bottom below viewport set bottom to bottom');
            scroll = $target.offset().top + $target.height() - $(window).height() + 30
        }
        if ($target.offset().top - scroll < navbar.height()) {
            //console.log('top too high- put highest allowed position');
            scroll = $target.offset().top - navbar.height()
        }
        if (scroll < 0) {
            //console.log('top of menu is below natural position - reset');
            scroll = 0;
        }
        if (scroll > 0 && scroll < $('#header').height()) {
            //console.log('dont show just a fraction of the header');
            scroll = $('#header').height() + 1;
        }


        if (scroll != $(window).scrollTop())
            $('html, body').animate({
                scrollTop: scroll
            }, 500);

    }


    /* Give "current" class to links to current page, and the list items that contain them */
    var currentPage = window.location.pathname;//+window.location.hash;
    $("a").each(function () {
        if ($(this).attr('href') == currentPage) {
            $(this).addClass("active");
            $(this).closest('li').addClass("active");
            var parent = $(this).parent().parent().closest('li');
            parent.addClass("active");
            parent.parent().closest('li').addClass("active");
        }
    });

    $("#site-tool").attr('href', '#'); //without javascript this links to the site-map page

    var $collapsedSubmenus = $('body:not(.site-map) #nav ul ul').addClass("collapse");

    $collapsedSubmenus.each(function () {
        var $a = $(this).prev();
        if ($a.attr('href') == '#') {
            $a.addClass('expandButton');
//                $a.append('<span class="glyphicon glyphicon-menu-down"></span>')
            //$a.append('<span class="caret"></span>') // _ns done in markup
        } else {
            var $expandButton = $('<span class="expandButton"><a href="#" class="glyphicon"></a></span>');
            $expandButton.insertBefore($(this));
        }
    });


    var $expandButtons = $("#nav span.expandButton a, #nav a.expandButton");
    $expandButtons.attr('aria-haspopup', true);
    $expandButtons.attr('aria-expanded', false);
    $expandButtons.click(function (e) {
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
            $target = $target.parent().closest('ul');
        } else {
//               Expand Menu
            $(this).attr('aria-expanded', true);
            $li.parent().find("ul.in").collapse('toggle');
            $li.parent().find("li.in a[aria-expanded=true]").attr('aria-expanded', false);
            $li.parent().find("li.in").removeClass('in');
            $target.collapse('toggle');
            $("#nav ul ul.hasOneIn").removeClass('hasOneIn');
            while ($li.size()) {
                $li = $li.addClass('in').closest('ul').addClass('hasOneIn').closest('li');
            }
        }
        window.setTimeout(
            function () {
                scrollIntoView($target)
            },
            600
        );
        return false;
    });
});
