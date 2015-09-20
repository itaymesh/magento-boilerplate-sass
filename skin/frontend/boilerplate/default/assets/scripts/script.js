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
};

jQuery(document).ready(function ($) {

    var miniCart = $('#header-cart')


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