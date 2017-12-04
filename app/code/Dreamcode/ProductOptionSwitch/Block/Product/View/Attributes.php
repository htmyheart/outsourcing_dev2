<?php

namespace Dreamcode\ProductOptionSwitch\Block\Product\View;

use Magento\Catalog\Model\Product;

/**
 * Product attributes block.
 */
class Attributes extends \Magento\Catalog\Block\Product\View\Attributes
{
    /**
     * @var Product
     */
    protected $_product = null;

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = null;
        }

        return $this->_product;
    }

    /**
     * @return Product
     */
    public function setProduct($product)
    {
        if ($product) {
            $this->_product = $product;
        }

        return $this->_product;
    }

}
