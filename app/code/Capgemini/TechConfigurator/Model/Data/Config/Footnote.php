<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\Data\Config;

/**
 * Footnote data model
 */
class Footnote extends \Magento\Framework\Model\AbstractModel
    implements \Capgemini\TechConfigurator\Api\Data\FootnoteInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Capgemini\TechConfigurator\Model\ResourceModel\Config\Footnote::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->getData('entity_id');
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {
        return $this->setData('entity_id', $id);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigId()
    {
        return $this->getData('config_id');
    }

    /**
     * {@inheritDoc}
     */
    public function setConfigId($configId)
    {
        return $this->setData('config_id', $configId);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumber()
    {
        return $this->getData('number');
    }

    /**
     * {@inheritDoc}
     */
    public function setNumber($number)
    {
        return $this->setData('number', $number);
    }

    /**
     * {@inheritDoc}
     */
    public function getFootnote()
    {
        return $this->getData('footnote');
    }

    /**
     * {@inheritDoc}
     */
    public function setFootnote($footnote)
    {
        return $this->setData('footnote', $footnote);
    }
}