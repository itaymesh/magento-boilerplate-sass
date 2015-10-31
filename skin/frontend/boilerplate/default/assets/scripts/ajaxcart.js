(function ($) {

    $.widget("boilerplate.ajaxCart", {
        options: {
            ajaxParams: [
                {'name': 'awacp', 'value': 1},
                {'name': 'no_cache', 'value': 1}
            ],
            minicart: ".header-minicart",
            minicartCounter: ".header-minicart > a .count",
            minicartContainer: '#header-cart',
            listItemCssClass : '.item'
        },

        /**
         * create quick view widget
         * @private
         */
        _create: function () {


            var self = this,
                populateOption = self.options["populate"];


            // Stores a reference to the parent Widget class
            self.widgetProto = $.Widget.prototype;

            // The original select box DOM element
            self.originalElem = self.element[0];


            self._eventHandlers();
        },

        /**
         * bind event handlers
         * @private
         */
        _eventHandlers: function () {

            var self = this,
                element = self.element;

            if (element.is('button')) {
                element.attr('data-onclick', element.attr('onclick')).attr('onclick', '');
                element.on('click.ajaxcart', function (e) {
                    this.blur();
                    if (productAddToCartForm.validator.validate()) {
                        self._prepareAndAddProduct();
                    }
                });
            }
            else {

                element.on('click.ajaxcart', function (e) {

                    e.preventDefault();

                    var url = $(this).attr('href');
                    self._getQuickView(url);

                });
            }


        },

        _prepareAndAddProduct: function () {
            var addProductForm = $('#product_addtocart_form');
            var action = addProductForm.attr('action');

            action = encodeURI(action);

            this._addToCart(action, addProductForm.serializeArray());
        },

        /**
         * execute AJAX request
         * @param String url
         * @param Object params
         * @param Function callback
         * @private
         */
        _ajaxRequest: function (url, params, callback) {

            var self = this;

            $.merge(params, this.options.ajaxParams);

            var url = url.replace(/http[^:]*:/, document.location.protocol);

            $.ajax({
                'url': url,
                'dataType': 'json',
                'type': 'GET',
                'data': params,

                success: function (response) {

                    self.element.trigger('onSuccess', [self, response]);

                    if ($.isFunction(callback)) callback(response);
                    0

                },

                error: function (jqXHR, textStatus, errorThrown) {
                    self.element.trigger('onError', [self, textStatus, errorThrown]);
                }
            });
        },

        /**
         * add to cart action
         * @param String url
         * @param Object params
         * @private
         */
        _addToCart: function (url, params) {

            var self = this;

            params.push({'name': 'block[]', 'value': ['minicart']});

            this._ajaxRequest(url, params, function (response) {


                if (response.success) {
                    //if (response.status == 'success') {
                    self._onSuccess(response);
                    //}
                    //else {
                    self._onFailed(response);
                    //}
                }

                else {
                    self._onFailed(response);
                }
            });
        },

        /**
         * handle add to cart success response
         * @param Object response
         * @private
         */
        _onSuccess: function (response) {
            this._refreshMiniCart(response);
            $j.magnificPopup.close();
        },

        _refreshMiniCart: function (response) {
            $j(this.options.minicartContainer).html(response.blocks.minicart);
            $j(this.options.minicartCounter).html(response.cart.count);
            $j(this.options.minicart).addClass('open');
        },


        /**
         * handle add to cart failed response
         * @param Object response
         * @private
         */
        _onFailed: function (response) {

        },

        /**
         * get quick view content and open popup
         * @param String url product URL
         * @private
         */
        _getQuickView: function (url) {

            if (window._quickViewLoading) return;

            window._quickViewLoading = true;


            // look all
            var self = this,
                url = url.replace(/http[^:]*:/, document.location.protocol);

            self.element.trigger('onBeforeQuickView', [self]);

            var params = [
                {'name': 'block[]', 'value': ['product']}
            ];


            url = encodeURI(url);


            this._getListItem().loadingOverlay();

            self._ajaxRequest(url, params, function (response) {

                if (response.blocks && response.blocks.product) {
                    /* $.unblockUI();*/

                    $.magnificPopup.open({
                        //closeBtnInside : false,
                        items: {
                            src: response.blocks.product,
                            type: 'inline'
                        },
                        mainClass: 'mfp-quick-view',
                        callbacks: {
                            open: function () {
                                var form = $j('#product_addtocart_form_ac');

                                form.submit(function (e) {
                                    e.preventDefault();
                                    var action = form.attr('action');
                                    self._addToCart(action, form.serializeArray());

                                });

                            },
                            close: function () {
                                window._quickViewLoading = false;
                                self._getListItem().loadingOverlay('remove');
                            }
                        }
                    });
                }

            });
        },

        _getListItem : function () {
            return this.element.closest(this.options.listItemCssClass);
        },
        _isMobile: function () {
            // Adapted from http://www.detectmobilebrowsers.com
            var ua = navigator.userAgent || navigator.vendor || window.opera;

            // Checks for iOs, Android, Blackberry, Opera Mini, and Windows mobile devices
            return (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua);
        }


    });

})(jQuery);