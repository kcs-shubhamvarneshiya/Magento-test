<?php

namespace Capgemini\Company\Plugin\Company;

use Magento\Company\Model\ResourceModel\Company;
use Magento\Framework\Model\AbstractModel;

/**
 * Plugin for \Magento\Company\Model\ResourceModel\Company
 */
class ConllectionsPreparePlugin
{
    /**
     * Implode collections into string
     *
     * @param Company $subject
     * @param AbstractModel $object
     * @return array
     */
    public function beforeSave(Company $subject, AbstractModel $object): array
    {
        if (is_array($object->getData('collections'))) {
            $object->setData('collections', implode(',', $object->getData('collections')));
        }
        return [$object];
    }
}