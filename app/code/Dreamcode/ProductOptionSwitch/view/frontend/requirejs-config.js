var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Dreamcode_ProductOptionSwitch/js/model/skuswitch': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Dreamcode_ProductOptionSwitch/js/model/swatch-skuswitch': true
            }
        }
    }
};