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

namespace PHPCuong\RestrictProduct\Plugin\CatalogSearch\Search;

class IndexBuilder
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param \Magento\CatalogSearch\Model\Search\IndexBuilder $indexBuilder
     * @param \Magento\Framework\DB\Select $select
     * @return \Magento\Framework\DB\Select
     */
    public function afterBuild(\Magento\CatalogSearch\Model\Search\IndexBuilder $indexBuilder, $select)
    {
        $customer = $this->customerSession->getCustomer();
        $customerGroupId = 0;
        if (!empty($customer->getId())) {
            $customerGroupId = $customer->getGroupId();
        }
        // Retrieve the product IDs are restricted
        $productIds = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('customer_group', ['finset' => (int)$customerGroupId])
            ->getAllIds();
        // Skip the products are restricted in the result
        if ($productIds) {
            $select = $select->where('search_index.entity_id NOT IN(?)', $productIds);
        }
        return $select;
    }
}
