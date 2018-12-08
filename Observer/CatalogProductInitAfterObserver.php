<?php
/**
 * GiaPhuGroup Co., Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://www.giaphugroup.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    PHPCuong
 * @package     PHPCuong_RestrictProduct
 * @copyright   Copyright (c) 2018-2019 GiaPhuGroup Co., Ltd. All rights reserved. (http://www.giaphugroup.com/)
 * @license     https://www.giaphugroup.com/LICENSE.txt
 */

namespace PHPCuong\RestrictProduct\Observer;

class CatalogProductInitAfterObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->customerSession = $customerSession;
    }

    /**
     * Execute observer.
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        if ($product) {
            // Get the current customer by session.
            $customer = $this->customerSession->getCustomer();
            $customerGroupId = 0;
            // If the current customer isn't a Guest
            if (!empty($customer->getId())) {
                // Modify the customer groupd id
                $customerGroupId = $customer->getGroupId();
            }
            //  Get the customer group ids of the current product. The result will be 0,1 or 0 or 1,2,3, etc,...
            $restrictCustomerGroup = $product->getCustomerGroup();
            // Convert the string to array.
            $customerGroupIds = explode(',', $restrictCustomerGroup);
            // If the group id of the current customer is in array, redirecting to the 404 page.
            if (in_array($customerGroupId, $customerGroupIds)) {
                // This helps us to redirect the product detail page to the 404 page, whenever this product is restricted.
                $resultRedirect = $this->responseFactory->create();
                $resultRedirect->setRedirect($this->url->getUrl('noroute'))->sendResponse('200');
                exit();
            }
        }
        return $this;
    }
}
