<?php

namespace Dreamcode\Customize\Block\Category;

/*
 * Use in cms block:
 * {{block class="Dreamcode\Customize\Block\Category\ListCategory" name="list_category_home_page" categoryIds="17,11,30,32,33,34" template="Dreamcode_Customize::category/list_category_homepage.phtml" }}
 * {{block class="Dreamcode\Customize\Block\Category\ListCategory" name="list_sub_category" template="Dreamcode_Customize::category/list_sub_category.phtml" }}
 * */

use Magento\Framework\Registry;

/**
 * ListCategory
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ListCategory extends \Magento\Framework\View\Element\Template
{

    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'Dreamcode_Customize::category/list_category_homepage.phtml';

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;


    protected $_categoryIds = '';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    private $registry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory  $categoryFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryFactory = $categoryFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->imageHelper = $imageHelper;
        $this->registry = $registry;
    }

    public function getCategoryIds()
    {
        if(!$this->_categoryIds){
            $this->_categoryIds = $this->getData('categoryIds');
        }
        return $this->_categoryIds;
    }

    public function getCategories()
    {
        $result = [];

        $categoryIds = $this->getCategoryIds();
        if($categoryIds != ''){
            $result = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect('*')
                ->addAttributeToFilter('is_active', 1)
                ->setOrder('position', 'ASC')
                ->addFieldToFilter('entity_id', ['in' => $categoryIds]);
        }

        return $result;
    }

    public function getPlaceholderImage()
    {
        return $this->imageHelper->getPlaceholder('image');
    }

    public function getCurrentCategory()
    {
        $category = $this->registry->registry('current_category');
        return $category;
    }

    public function getCategoryList()
    {
        $result = [];
        $_category = $this->getCurrentCategory();

        if($_category){
            $result = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect('*')
                ->addAttributeToFilter('is_active', 1)
                ->setOrder('position', 'ASC')
                ->addIdFilter($_category->getChildren());

        }
        return $result;
    }
}
