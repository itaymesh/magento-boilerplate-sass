/**
 * Studio Raz.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the  Studio Raz EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://studioraz.co.il/eula.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://studioraz.co.il/ for more information
 * or send an email to support@studioraz.co.il
 *
 * @category    skin
 * @package     boilerplate_default
 * @copyright   Copyright (c) 2011-2015 Studio Raz. (http://www.studioraz.co.il)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Extend Varien Accordion
 */

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