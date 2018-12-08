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

namespace PHPCuong\RestrictProduct\Model\Attribute\Source;

class CustomerGroup extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $collectionFactory
     */
    public function __construct(\Magento\Customer\Model\ResourceModel\Group\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get all options of customer group
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->collectionFactory->create()->toOptionArray();
        }
        return $this->_options;
    }
}
