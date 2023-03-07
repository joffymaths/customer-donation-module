define([
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals'

], function (ko, Component, quote, priceUtils, totals) {
    'use strict';
    var show_hide_donation_blockConfig = window.checkoutConfig.show_hide_donation_block;
    var donation_label = window.checkoutConfig.donation_label;
    var donation_amount = window.checkoutConfig.donation_amount;


    return Component.extend({
        totals: quote.getTotals(),
        getFormattedPrice: ko.observable(priceUtils.formatPrice(donation_amount, quote.getPriceFormat())),
        getDonationLabel:ko.observable(donation_label),

 
        isDisplayed: function () {
            return this.getValue() != 0;
        },
        isDisplayBoth: function () {
            return window.checkoutConfig.displayBoth;
        },
        getValue: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('donation')) {
                price = totals.getSegment('donation').value;
            }
            return price;
        },
        getInFormattedPrice: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('donation')) {
                price = totals.getSegment('donation').value;
            }

            return priceUtils.formatPrice(price, quote.getPriceFormat());
        },
    });
});