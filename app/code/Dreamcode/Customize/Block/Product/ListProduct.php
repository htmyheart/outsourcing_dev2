<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Dreamcode\ShowSubCategories\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Product list
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $image;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,  
        \Magento\Catalog\Helper\Category $categoryHelper, 
        \Magento\Catalog\Model\CategoryFactory  $categoryFactory,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );

        $this->_categoryFactory = $categoryFactory;
        $this->image = $context->getImageHelper();
    }

    public function getCategoryList()
    {
        $_category  = $this->getLayer()->getCurrentCategory();
        $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', 1)
            ->setOrder('position', 'ASC')
            ->addIdFilter($_category->getChildren());
      return $collection;
      
    }
    public function getCategoryThumbImage($imageName) {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaDirectory . 'catalog/category/' . $imageName;
       
    }
   
    public function getPlaceholderImage(){
        return $this->image->getPlaceholder('image');
    }
}
