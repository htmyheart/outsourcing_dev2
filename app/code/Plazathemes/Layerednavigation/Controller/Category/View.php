<?php
/**
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

namespace Plazathemes\LayeredNavigation\Controller\Category;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View extends \Magento\Catalog\Controller\Category\View
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Catalog session
     *
     * @var \Magento\Catalog\Model\Session
     */
    protected $_catalogSession;

    /**
     * Catalog design
     *
     * @var \Magento\Catalog\Model\Design
     */
    protected $_catalogDesign;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator
     */
    protected $categoryUrlPathGenerator;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Model\Design $catalogDesign
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Design $catalogDesign,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct(
            $context,
            $catalogDesign,
            $catalogSession,
            $coreRegistry,
            $storeManager,
            $categoryUrlPathGenerator,
            $resultPageFactory,
            $resultForwardFactory,
            $layerResolver,
            $categoryRepository
        );
        $this->_storeManager = $storeManager;
        $this->_catalogDesign = $catalogDesign;
        $this->_catalogSession = $catalogSession;
        $this->_coreRegistry = $coreRegistry;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->layerResolver = $layerResolver;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute()
    {
        $layer_action = $this->getRequest()->getParam('layer_action');
        if($layer_action == 1) {
            if ($this->_request->getParam(\Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED)) {
                return $this->resultRedirectFactory->create()->setUrl($this->_redirect->getRedirectUrl());
            }

            $category = $this->_initCategory();
           
            if ($category) {
                $this->layerResolver->create(Resolver::CATALOG_LAYER_CATEGORY);
                $settings = $this->_catalogDesign->getDesignSettings($category);

                // apply custom design
                if ($settings->getCustomDesign()) {
                    $this->_catalogDesign->applyCustomDesign($settings->getCustomDesign());
                }

                $this->_catalogSession->setLastViewedCategoryId($category->getId());

                $page = $this->resultPageFactory->create();
                // apply custom layout (page) template once the blocks are generated
                if ($settings->getPageLayout()) {
                    $page->getConfig()->setPageLayout($settings->getPageLayout());
                }
                if ($category->getIsAnchor()) {
                    $type = $category->hasChildren() ? 'layered' : 'layered_without_children';
                } else {
                    $type = $category->hasChildren() ? 'default' : 'default_without_children';
                }

                if (!$category->hasChildren()) {
                    // Two levels removed from parent.  Need to add default page type.
                    $parentType = strtok($type, '_');
                    $page->addPageLayoutHandles(['type' => $parentType]);
                }
                $page->addPageLayoutHandles(['type' => $type, 'id' => $category->getId()]);

                // apply custom layout update once layout is loaded
                $layoutUpdates = $settings->getLayoutUpdates();
                if ($layoutUpdates && is_array($layoutUpdates)) {
                    foreach ($layoutUpdates as $layoutUpdate) {
                        $page->addUpdate($layoutUpdate);
                    }
                }

                $product_list = $page->getLayout()->getBlock('category.products.list')->toHtml();
                if($page->getLayout()->getBlock('catalog.leftnav')) {
                    $leftLayer = $page->getLayout()->getBlock('catalog.leftnav')->toHtml();
                } else {
                    $leftLayer = false;
                }
                

                $data['leftLayer'] = $leftLayer;
                $data['productlist'] = $product_list;

                $this->getResponse()->representJson(
                    $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($data)
                );
            } elseif (!$this->getResponse()->isRedirect()) {
                return $this->resultForwardFactory->create()->forward('noroute');
            }
        } else {
            return parent::execute(); // TODO: Change the autogenerated stub
        }
    }
}