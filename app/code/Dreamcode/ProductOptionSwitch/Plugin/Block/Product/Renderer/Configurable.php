<?php
namespace Dreamcode\ProductOptionSwitch\Plugin\Block\Product\Renderer;

class Configurable
{
    /** @var \Magento\Catalog\Helper\Output */
    protected $outputHelper;

    /** @var \Magento\Catalog\Model\ProductRepository $productRepository */
    protected $_productRepository;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @param \Magento\Catalog\Helper\Output $outputHelper
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->outputHelper = $outputHelper;
        $this->_productRepository = $productRepository;
        $this->layout = $layout;
    }

    public function afterGetJsonConfig(\Magento\Swatches\Block\Product\Renderer\Configurable $subject, $result)
    {
        $jsonResult = json_decode($result, true);
        $jsonResult['skus'] = [];
        $jsonResult['descriptions'] = [];
        $jsonResult['short_descriptions'] = [];
        $jsonResult['technical_data'] = [];
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $product = $this->getProductById($simpleProduct->getId());
            $jsonResult['skus'][$product->getId()] = $product->getSku();
            $jsonResult['descriptions'][$product->getId()] = $product->getDescription();
            $jsonResult['short_descriptions'][$product->getId()] = $product->getShortDescription();
            $jsonResult['technical_data'][$product->getId()] = $this->getAttributeDataHtml($product);
        }
        $result = json_encode($jsonResult);
        return $result;
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    protected function getAttributeDataHtml($product)
    {
        /** @var $block \Dreamcode\ProductOptionSwitch\Block\Product\View\Attributes */
        $block = $this->layout->createBlock('Dreamcode\ProductOptionSwitch\Block\Product\View\Attributes');
        $block->setProduct($product);
        $block->setTemplate('Magento_Catalog::product/view/attributes.phtml');

        return $block->toHtml();
    }
}