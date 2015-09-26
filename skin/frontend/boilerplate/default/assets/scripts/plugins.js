(function ($) {
    'use strict';
    $.fn.cycleEvents = function () {

        return this.each(function () {
            var cycle = $(this);

            cycle.on('cycle-bootstrap', function (event, opts) {
                opts.container.addClass('cycle-fx-' + opts.fx);

                var slides = opts.container.find(opts.slides);
                var slidesCount = slides.size();

                // create slideshow wrapper
                var wrapper = opts.container.wrap('<div class="slideshow-wrapper" />').parent();
                var id = cycle.attr('id');
                wrapper.attr('id', id + '-wrapper');

                if (opts.showArrows && (slidesCount > 1 && opts.fx != 'carousel') || (opts.fx == 'carousel' && opts.carouselVisible < slidesCount)) {

                    // append slideshow nav controls
                    wrapper.append('<div class="slideshow-nav slideshow-prev"></div><div class="slideshow-nav slideshow-next "></div>');

                    $.extend(opts, {
                        prev: wrapper.children('.slideshow-next'),
                        next: wrapper.children('.slideshow-prev'),
                        swipe: true
                    });
                }
                if (opts.fx == 'carousel') {

                    if (opts.carouselVisible < slidesCount) {


                    } else {
                        var carouselVisible = opts.carouselVisible;

                        // delete this property so cycle will not try to create a carousel from it and breaks the layout.
                        delete opts.carouselVisible;

                        if (carouselVisible > 1) {
                            //var carouselVisible = opts.carouselVisible;
                            var cycleEventOnResize = function () {
                                var slide = slides.eq(0);
                                var adjustment = slide.outerWidth() - slide.width();
                                var slideWidth = opts.container.width() / carouselVisible;
                                slideWidth = Math.ceil(slideWidth - adjustment);
                                slides.width(slideWidth);
                            }
                            cycleEventOnResize();
                            $(window).on('resize', cycleEventOnResize);
                        }
                    }
                }

            })
        });
    }
})(jQuery);


// Extend varian Accordion

var AccordionOnepage = Class.create();

AccordionOnepage.prototype = {

    initialize: function (elem, clickableEntity, checkAllow) {
        this.container = $(elem);
        this.checkAllow = checkAllow || false;
        this.disallowAccessToNextSections = false;
        this.sections = $$('#' + elem + ' .section');


        this.currentSection = false;

        //var headers = $$('#' + elem + ' .section ' + clickableEntity);

        var headers = $$('#' + elem + ' .opc-tab');

        this.headers = headers;

        this.headers.each(function (header) {
            Event.observe(header, 'click', this.sectionClicked.bindAsEventListener(this));
        }.bind(this));
    },

    sectionClicked: function (event) {
        var sectionId = event.currentTarget.id.replace('tab-', '');
        this.openSection(sectionId);
        Event.stop(event);
    },

    openSection: function (section) {
        var section = $(section);

        // Check allow
        if (this.checkAllow && !Element.hasClassName(section, 'allow')) {
            return;
        }

        if (section.id != this.currentSection) {
            this.closeExistingSection();
            this.currentSection = section.id;

            $(this.currentSection).addClassName('active');

            if ($('tab-' + this.currentSection)) {
                $('tab-' + this.currentSection).addClassName('active').addClassName('allow');
            }


            var contents = Element.select(section, '.a-item');
            contents[0].show();

            if (this.disallowAccessToNextSections) {
                var pastCurrentSection = false;
                for (var i = 0; i < this.sections.length; i++) {
                    if (pastCurrentSection) {

                        Element.removeClassName(this.sections[i], 'allow');

                        Element.removeClassName(this.headers[i], 'allow');

                    }
                    if (this.sections[i].id == section.id) {
                        pastCurrentSection = true;
                    }
                }
            }
        }
    },

    closeSection: function (section) {

        $(section).removeClassName('active');
        if ($('tab-' + this.currentSection)) {
            $('tab-' + this.currentSection).removeClassName('active');
        }

        var contents = Element.select(section, '.a-item');
        contents[0].hide();
        //Effect.SlideUp(contents[0]);
    },

    openNextSection: function (setAllow) {
        for (section in this.sections) {
            var nextIndex = parseInt(section) + 1;
            if (this.sections[section].id == this.currentSection && this.sections[nextIndex]) {
                if (setAllow) {
                    Element.addClassName(this.sections[nextIndex], 'allow');
                    Element.addClassName(this.headers[nextIndex], 'allow');
                }
                this.openSection(this.sections[nextIndex]);

                return;
            }
        }
    },

    openPrevSection: function (setAllow) {
        for (section in this.sections) {
            var prevIndex = parseInt(section) - 1;
            if (this.sections[section].id == this.currentSection && this.sections[prevIndex]) {
                if (setAllow) {
                    Element.addClassName(this.sections[prevIndex], 'allow');
                    Element.addClassName(this.headers[prevIndex], 'allow')
                }
                this.openSection(this.sections[prevIndex]);
                return;
            }
        }
    },

    closeExistingSection: function () {
        if (this.currentSection) {
            this.closeSection(this.currentSection);
        }
    }
};

(function ($) {
    'use strict';

    $.widget('modli.loadingOverlay', {

        options: {
            loaderElement: '<div class="loading-overlay" />',
            loaderCss: {
                position: 'absolute',
                top: '0',
                bottom: '0',
                left: '0',
                right: '0',
                background: 'rgba(255,255,255,0.8)'
            },
            spin: {
                size: 'small'
            }
        },

        _isLoading: false,

        _create: function () {
            this.element.css('position', 'relative');
        },

        _loader: function () {

        },

        show: function () {
            this._isLoading = true;
            this.loaderElement = $(this.options.loaderElement).css(this.options.loaderCss)
                .appendTo(this.element).spin(this.options.spin.size);
        },

        hide: function () {
            this._isLoading = false;
            this.loaderElement.remove();
        },

        isLoading: function () {
            return this._isLoading;
        }

    })
})(jQuery);


(function ($) {
    'use strict';
// ==============================================
// UI Pattern - Toggle Content (tabs and accordions in one setup)
// ==============================================
    $.fn.toggleContent = function () {

        return this.each(function () {
            var wrapper = jQuery(this);

            var hasTabs = wrapper.hasClass('tabs');
            var hasAccordion = wrapper.hasClass('accordion');
            var startOpen = wrapper.hasClass('open');

            var dl = wrapper.children('dl:first');
            var dts = dl.children('dt');
            var panes = dl.children('dd');
            var groups = new Array(dts, panes);

            //Create a ul for tabs if necessary.
            if (hasTabs) {
                var ul = jQuery('<ul class="toggle-tabs"></ul>');
                dts.each(function () {
                    var dt = jQuery(this);
                    var li = jQuery('<li></li>');
                    li.html(dt.html());
                    ul.append(li);
                });
                ul.insertBefore(dl);
                var lis = ul.children();
                groups.push(lis);
            }

            //Add "last" classes.
            var i;
            for (i = 0; i < groups.length; i++) {
                groups[i].filter(':last').addClass('last');
            }

            function toggleClasses(clickedItem, group) {
                var index = group.index(clickedItem);
                var i;
                for (i = 0; i < groups.length; i++) {
                    groups[i].removeClass('current');
                    groups[i].eq(index).addClass('current');
                }
            }

            //Toggle on tab (dt) click.
            dts.on('click', function (e) {
                //They clicked the current dt to close it. Restore the wrapper to unclicked state.
                if (jQuery(this).hasClass('current') && wrapper.hasClass('accordion-open')) {
                    wrapper.removeClass('accordion-open');
                } else {
                    //They're clicking something new. Reflect the explicit user interaction.
                    wrapper.addClass('accordion-open');
                }
                toggleClasses(jQuery(this), dts);
            });

            //Toggle on tab (li) click.
            if (hasTabs) {
                lis.on('click', function (e) {
                    toggleClasses(jQuery(this), lis);
                });
                //Open the first tab.
                lis.eq(0).trigger('click');
            }

            //Open the first accordion if desired.
            if (startOpen) {
                dts.eq(0).trigger('click');
            }
        })
    };


    // ==============================================
    // UI Pattern - ToggleSingle
    // ==============================================

    // Use this plugin to toggle the visibility of content based on a toggle link/element.
    // This pattern differs from the accordion functionality in the Toggle pattern in that each toggle group acts
    // independently of the others. It is named so as not to be confused with the Toggle pattern below
    //
    // This plugin requires a specific markup structure. The plugin expects a set of elements that it
    // will use as the toggle link. It then hides all immediately following siblings and toggles the sibling's
    // visibility when the toggle link is clicked.
    //
    // Example markup:
    // <div class="block">
    //     <div class="block-title">Trigger</div>
    //     <div class="block-content">Content that should show when </div>
    // </div>
    //
    // JS: jQuery('.block-title').toggleSingle();
    //
    // Options:
    //     destruct: defaults to false, but if true, the plugin will remove itself, display content, and remove event handlers


    jQuery.fn.toggleSingle = function (options) {

        // passing destruct: true allows
        var settings = $j.extend({
            destruct: false
        }, options);

        return this.each(function () {
            if (!settings.destruct) {
                $j(this).on('click', function () {
                    $j(this)
                        .toggleClass('active')
                        .next()
                        .toggleClass('no-display');
                });
                // Hide the content
                $j(this).next().addClass('no-display');
            } else {
                // Remove event handler so that the toggle link can no longer be used
                $j(this).off('click');
                // Remove all classes that were added by this plugin
                $j(this)
                    .removeClass('active')
                    .next()
                    .removeClass('no-display');
            }

        });
    }
})(jQuery);