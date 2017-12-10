/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function(targetModule){

        var updatePrice = targetModule.prototype._UpdatePrice;
        targetModule.prototype.configurableSku = $('div.product-info-main .sku .value').html();

        var default_product_name = $('div.product-info-main h1.page-title span').html();
        var default_simpleSku = $('div.product-info-main .sku .value').html();
        var default_short_descriptions = $('div.product-info-main .box-info-des .value').html();
        var default_descriptions = $('div.product-info-main .product .content .description .value').html();
        var default_technical_data = $('#additionaltechdata').html();

        var updatePriceWrapper = wrapper.wrap(updatePrice, function(original){
            //do extra stuff
            var allSelected = true;
            for(var i = 0; i<this.options.jsonConfig.attributes.length;i++){
                if (!$('div.product-info-main .product-options-wrapper .swatch-attribute.' + this.options.jsonConfig.attributes[i].code).attr('option-selected')){
                    allSelected = false;
                }
            }

            var simpleSku = this.configurableSku;
            if (allSelected){
                var products = this._CalcProducts();
                simpleSku = this.options.jsonConfig.skus[products.slice().shift()];
                $('div.product-info-main h1.page-title span').html(this.options.spConfig.product_name[this.simpleProduct]);
                $('div.product-info-main .sku .value').html(simpleSku);
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

        targetModule.prototype._UpdatePrice = updatePriceWrapper;
        return targetModule;
    };
});