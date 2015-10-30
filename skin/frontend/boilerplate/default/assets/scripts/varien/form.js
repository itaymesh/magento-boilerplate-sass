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
 * Add AJAX functionality to default VarienForm class
 *
 * To AJAXify your VarienForm init it like this: new VarienForm([formId], [firstFieldFocus], { isAjax:true });
 */
VarienForm.prototype.initialize = function (formId, firstFieldFocus, options) {
    this.form = $(formId);
    if (!this.form) {
        return;
    }
    this.cache = $A();
    this.currLoader = false;
    this.currDataIndex = false;

    this.options = options || {};

    var validationOptions = {};

    if (this.options.isAjax) {

        this.ajaxMessage = new Element('p', {
            'class': 'ajax-message'
        }).hide();

        this.form.insert(this.ajaxMessage);

        Event.observe(this.form, 'submit', this.submitAjax.bind(this), false);

        validationOptions.onSubmit = false;
    };

    this.validator = new Validation(this.form, validationOptions);

    this.elementFocus = this.elementOnFocus.bindAsEventListener(this);
    this.elementBlur = this.elementOnBlur.bindAsEventListener(this);
    this.childLoader = this.onChangeChildLoad.bindAsEventListener(this);
    this.highlightClass = 'highlight';
    this.extraChildParams = '';
    this.firstFieldFocus = firstFieldFocus || false;
    this.bindElements();
    if (this.firstFieldFocus) {
        try {
            Form.Element.focus(Form.findFirstElement(this.form))
        }
        catch (e) {
        }
    }
};

VarienForm.prototype.submitAjax = function (ev) {

    Event.stop(ev);

    if (this.validator.validate()) {
        var parameters = this.form.serialize();
        parameters = parameters ? parameters + '&is_ajax=1' : parameters;
        new Ajax.Request(this.form.action, {
            method: 'post',
            parameters: parameters,
            onSuccess: this.onAjaxSuccess.bindAsEventListener(this),
            onComplete: this.onAjaxComplete.bindAsEventListener(this),
            onLoading: this.onAjaxLoading.bindAsEventListener(this)
        });
    }

};

VarienForm.prototype.onAjaxSuccess = function (response) {

    console.log(response);
    if (response.constructor == "function") {
        // TODO-SR: handle controllers that does not have ajax functionality
    }
    else {
        var removeClass = (response.responseJSON.status == 'success') ? 'error' : 'success';
        var addClass = (response.responseJSON.status == 'success') ? 'success' : 'error';

        this.ajaxMessage.removeClassName(removeClass).addClassName(addClass).show().innerHTML = response.responseJSON.message;
    }


}

VarienForm.prototype.onAjaxComplete = function (response) {
    if (response.status != 200) {
        // TODO-SR: handle server error
    }
};

VarienForm.prototype.onAjaxLoading = function () {
    this.ajaxMessage.hide();
}



