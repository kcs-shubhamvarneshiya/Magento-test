<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\ResourceModel\Config;

use Capgemini\TechConfigurator\Model\ResourceModel\Config\SkuPart\Option;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Sku Part resource model
 */
class SkuPart extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var Option
     */
    protected $optionResource;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Option $optionResource
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        Option $optionResource,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->optionResource = $optionResource;
    }

    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('vc_configurator_skupart', 'entity_id');
    }

    /**
     * {@inheritDoc}
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \Capgemini\TechConfigurator\Api\Data\SkuPartInterface $object */
        parent::_afterSave($object);

        $skuPartId = $object->getId();
        if ($object->getOptions()) {
            foreach ($object->getOptions() as $option) {
                $option->setSkupartId($skuPartId);
                $this->optionResource->save($option);
            }
        }
    }
}
