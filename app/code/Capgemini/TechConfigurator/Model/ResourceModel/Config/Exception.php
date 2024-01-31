<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\ResourceModel\Config;

use Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception\Condition;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Exception resource model
 */
class Exception extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var Condition
     */
    protected $conditionResource;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Condition $conditionResource
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        Condition $conditionResource,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->conditionResource = $conditionResource;
    }

    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('vc_configurator_exception', 'entity_id');
    }

    /**
     * {@inheritDoc}
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \Capgemini\TechConfigurator\Api\Data\ExceptionInterface $object */
        parent::_afterSave($object);

        $exceptionId = $object->getId();
        if ($object->getConditions()) {
            foreach ($object->getConditions() as $condition) {
                $condition->setExceptionId($exceptionId);
                $this->conditionResource->save($condition);
            }
        }
    }
}
