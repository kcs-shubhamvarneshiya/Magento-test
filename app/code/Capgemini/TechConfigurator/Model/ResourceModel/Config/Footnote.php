<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\ResourceModel\Config;

/**
 * Footnote resource model
 */
class Footnote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('vc_configurator_footnotes', 'entity_id');
    }
}
