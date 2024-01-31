<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\ResourceModel\Config\Footnote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Footnote collection
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct() : void
    {
        $this->_init('Capgemini\TechConfigurator\Model\Data\Config\Footnote',
            'Capgemini\TechConfigurator\Model\ResourceModel\Config\Footnote');
    }
}
