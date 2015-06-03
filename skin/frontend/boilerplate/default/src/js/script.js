var screenTablet = false;
var screenDesktop = false;


// =============================================
// Primary Break Points
// =============================================

// These should be used with the bp (max-width, xx) mixin
// where a min-width is used, remember to +1 to break correctly
// If these are changed, they must also be updated in _var.scss

var bp = {
    xsmall: 320,
    small: 480,
    medium: 768,
    large: 992,
    xlarge: 1200
}




enquire.register("screen and (max-width:992px)", {
    match: function () {
        //console.log('screen tablet');
        screenTablet = true;
    }
});

enquire.register("screen and (min-width:992px)", {
    match: function () {
        // console.log('screen desktop');
        screenDesktop = true;
    }
});

;(function($) {
    'use strict';

    function Site(settings) {

        this.windowLoaded = false;

    }

    Site.prototype = {
        constructor: Site

        , start: function() {
            var me = this;

            $(window).load(function() {
                me.windowLoaded = true;
            });

            this.attach();
        }

        , attach: function() {
            this.attachBootstrapPrototypeCompatibility();
            //this.attachMedia();
        }

        , attachBootstrapPrototypeCompatibility: function() {

            // Bootstrap and Prototype don't play nice, in the sense that
            // prototype is a really wacky horrible library. It'll
            // hard-code CSS to hide an element when a hide() event
            // is fired. See http://stackoverflow.com/q/19139063
            // To overcome this with dropdowns that are both
            // toggle style and hover style, we'll add a CSS
            // class which has "display: block !important"
            $('*').on('show.bs.dropdown show.bs.collapse', function(e) {
                $(e.target).addClass('bs-prototype-override');
            });

            $('*').on('hidden.bs.collapse', function(e) {
                $(e.target).removeClass('bs-prototype-override');
            });
        }

        , attachMedia: function() {
            var $links = $('[data-toggle="media"]');
            if ( ! $links.length) return;

            // When somebody clicks on a link, slide the
            // carousel to the slide which matches the
            // image index and show the modal
            $links.on('click', function(e) {
                e.preventDefault();

                var $link = $(this),
                   $modal = $($link.attr('href')),
                $carousel = $modal.find('.carousel'),
                    index = parseInt($link.data('index'));

                $carousel.carousel(index);
                $modal.modal('show');

                return false;
            });
        }
    }


    jQuery(document).ready(function($) {
        var site = new Site();
        site.start();
    });

})(jQuery);

var $j = jQuery.noConflict();

jQuery(document).ready(function ($) {
    /**
     * Bootstrap dropdown Show on mouse click
     */

    var navLi = $('a[data-toggle]');

    function bootstrapDropdown() {


        // http://jsfiddle.net/AndreasPizsa/NzvKC/
        var delay = 50;
        var theTimer = 0;
        var theElement = null;
        var theLastPosition = {x: 0, y: 0};
        if (screenDesktop) {
            navLi.on('click.desktop', function () {

                window.location = $(this).attr('href');

            })

                .parent()
                .on('mouseenter.desktop', function (inEvent) {
                    if (theElement) theElement.removeClass('open');
                    window.clearTimeout(theTimer);
                    theElement = $(this);

                    theTimer = window.setTimeout(function () {
                        theElement.addClass('open');
                    }, delay);
                })
                .on('mousemove.desktop', function (inEvent) {
                    if (Math.abs(theLastPosition.x - inEvent.ScreenX) > 4 ||
                        Math.abs(theLastPosition.y - inEvent.ScreenY) > 4) {
                        theLastPosition.x = inEvent.ScreenX;
                        theLastPosition.y = inEvent.ScreenY;
                        return;
                    }
                    if (theElement.hasClass('open')) return;
                    window.clearTimeout(theTimer);
                    theTimer = window.setTimeout(function () {
                        theElement.addClass('open');
                    }, delay);
                })
                .on('mouseleave.desktop',function (inEvent) {
                    window.clearTimeout(theTimer);
                    theElement = $(this);
                    theTimer = window.setTimeout(function () {
                        theElement.removeClass('open');
                    }, delay);
                }).on('click.desktop', function () {
                    $(this).addClass('bs-prototype-override');
                    // return false;
                });

        }
    }; // 200 is the delay in milliseconds

    bootstrapDropdown(jQuery, window, 50);


    // ==============================================
    // UI Pattern - Toggle Content (tabs and accordions in one setup)
    // ==============================================


    $('.toggle-content').toggleContent();



    // ==============================================
    // Block collapsing (on smaller viewports)
    // ==============================================

    enquire.register('(max-width: ' + bp.large + 'px)', {
        setup: function () {
            this.toggleElements = $(
                // This selects the menu on the My Account and CMS pages
                '.col-left-first .block:not(.block-layered-nav) .block-title, ' +
                '.col-left-first .block-layered-nav .block-subtitle--filter, ' +
                '.sidebar:not(.col-left-first) .block .block-title'
            );
        },
        match: function () {
            this.toggleElements.toggleSingle();
        },
        unmatch: function () {
            this.toggleElements.toggleSingle({destruct: true});
        }
    });
});