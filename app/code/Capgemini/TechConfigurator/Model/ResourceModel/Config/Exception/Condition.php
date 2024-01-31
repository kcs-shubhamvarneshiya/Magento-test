<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception;

/**
 * Condition resource model
 */
class Condition extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('vc_configurator_exception_condition', 'entity_id');
    }
}
