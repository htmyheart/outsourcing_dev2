var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Dreamcode_ProductOptionSwitch/js/model/product-option-switch': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Dreamcode_ProductOptionSwitch/js/model/swatch-product-option-switch': true
            }
        }
    }
};