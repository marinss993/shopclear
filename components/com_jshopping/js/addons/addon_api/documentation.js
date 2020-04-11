/*
* @version      1.0.1 23.02.2018
* @author       MAXXmarketing GmbH
* @package      addon_api
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

jQuery(document).ready(function($) {
    window.onscroll  = function() {
        var scroll   = window.pageYOffset || document.documentElement.scrollTop,
            main_top = $('main').offset().top;
        $('menu').css(
            {
                top: (scroll > main_top ? 10 : (main_top - scroll)) + 'px'
            }
        );
        clearTimeout($.data(this, 'scrollCheck'));
        $.data(
            this,
            'scrollCheck',
            setTimeout(
                function() {
                    var section_id = '',
                        sections   = $('section');
                    sections.each(function(i, el) {
                        var id = $(el).attr('id');
                        if (
                            typeof id !== "undefined" &&
                            scroll > $(el).offset().top - 1
                        ) {
                            section_id = id;
                            $('menu a[href=\'#' + id  + '\']').click();
                            return;
                        }
                    });
                    if (!section_id.length) {
                        $('menu a[href=\'#' + sections.first().attr('id')  + '\']').click();
                    }
                },
                250
            )
        );
    };
    window.onscroll();
    $('menu a').click(function() {
        $('menu li.active').removeClass('active');
        $(this).parents('li').addClass('active');
    });
    $('menu a[href=\'' + window.location.hash  + '\']').click();
    $('.details-btn').click(function() {
        var el = $(this);
        el.hasClass('open')
        ? el.toggleClass('open close').closest('tr').next('.details').hide()
        : el.addClass('open').closest('tr').next('.details').show()
    });
});