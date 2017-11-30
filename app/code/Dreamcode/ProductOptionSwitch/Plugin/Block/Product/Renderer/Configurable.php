<?php
namespace Dreamcode\ProductOptionSwitch\Plugin\Block\Product\Renderer;

class Configurable
{
    /** @var \Magento\Catalog\Helper\Output */
    protected $outputHelper;

    /** @var \Magento\Catalog\Model\ProductRepository $productRepository */
    protected $_productRepository;

    /**
     * @param \Magento\Catalog\Helper\Output $outputHelper
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     */
    public function __construct(
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->outputHelper = $outputHelper;
        $this->_productRepository = $productRepository;
    }

    public function afterGetJsonConfig(\Magento\Swatches\Block\Product\Renderer\Configurable $subject, $result)
    {
        $jsonResult = json_decode($result, true);
        $jsonResult['skus'] = [];
        $jsonResult['descriptions'] = [];
        $jsonResult['short_descriptions'] = [];
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $product = $this->getProductById($simpleProduct->getId());
            $jsonResult['skus'][$product->getId()] = $product->getSku();
            $jsonResult['descriptions'][$product->getId()] = $product->getDescription();
            $jsonResult['short_descriptions'][$product->getId()] = $product->getShortDescription();
        }
        $result = json_encode($jsonResult);
        return $result;
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    public function xlog($message = 'null')
    {
        $log = print_r($message, true);
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get('Psr\Log\LoggerInterface')
            ->debug($log);
    }
}