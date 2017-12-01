<?php
namespace Dreamcode\Customize\Block\Product;

/*
 * Use in cms block:
 * {{block class="Dreamcode\Customize\Block\Product\ListProduct" name="list_product_wheels" template="Dreamcode_Customize::category/product/wheel_table.phtml" }}
 * {{block class="Dreamcode\Customize\Block\Product\ListProduct" name="list_product_casters" template="Dreamcode_Customize::category/product/caster_table.phtml" }}
 *  */

/**
 * Product list
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'Dreamcode_Customize::category/product/wheel_table.phtml';
}
