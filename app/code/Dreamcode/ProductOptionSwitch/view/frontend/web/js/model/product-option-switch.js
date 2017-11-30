define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function(targetModule){

        var reloadPrice = targetModule.prototype._reloadPrice;
        targetModule.prototype.configurableSku = $('div.product-info-main .sku .value').html();

        var reloadPriceWrapper = wrapper.wrap(reloadPrice, function(original){
            //do extra stuff
            var simpleSku = this.configurableSku;

            if(this.simpleProduct){
                simpleSku = this.options.spConfig.skus[this.simpleProduct];

                $('div.product-info-main .sku .value').html(simpleSku);
                $('div.product-info-main .box-info-des .value').html(this.options.spConfig.descriptions[this.simpleProduct]);
                $('div.product-info-main .product .content .description .value').html(this.options.spConfig.short_descriptions[this.simpleProduct]);
                $('#additionaltechdata').html(this.options.spConfig.technical_data[this.simpleProduct]);

            }

            //return original value
            return original();
        });

        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});