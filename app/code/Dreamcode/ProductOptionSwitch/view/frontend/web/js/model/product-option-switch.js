define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function(targetModule){

        var reloadPrice = targetModule.prototype._reloadPrice;
        targetModule.prototype.configurableSku = $('div.product-info-main .sku .value').html();

        var default_product_name = $('div.product-info-main h1.page-title span').html();
        var default_simpleSku = $('div.product-info-main .sku .value').html();
        var default_short_descriptions = $('div.product-info-main .box-info-des .value').html();
        var default_descriptions = $('div.product-info-main .product .content .description .value').html();
        var default_technical_data = $('#additionaltechdata').html();

        var reloadPriceWrapper = wrapper.wrap(reloadPrice, function(original){
            //do extra stuff
            var simpleSku = this.configurableSku;


            if(this.simpleProduct){
                $('div.product-info-main h1.page-title span').html(this.options.spConfig.product_name[this.simpleProduct]);
                $('div.product-info-main .sku .value').html(this.options.spConfig.skus[this.simpleProduct]);
                $('div.product-info-main .box-info-des .value').html(this.options.spConfig.short_descriptions[this.simpleProduct]);
                $('div.product-info-main .product .content .description .value').html(this.options.spConfig.descriptions[this.simpleProduct]);
                $('#additionaltechdata').html(this.options.spConfig.technical_data[this.simpleProduct]);
            }else{
                $('div.product-info-main h1.page-title span').html(default_product_name);
                $('div.product-info-main .sku .value').html(default_simpleSku);
                $('div.product-info-main .box-info-des .value').html(default_short_descriptions);
                $('div.product-info-main .product .content .description .value').html(default_descriptions);
                $('#additionaltechdata').html(default_technical_data);
            }



            //return original value
            return original();
        });

        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});