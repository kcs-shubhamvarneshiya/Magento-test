<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\Data;

/**
 * Product Configurator Config data model
 */
class Config extends \Magento\Framework\Model\AbstractModel
    implements \Capgemini\TechConfigurator\Api\Data\ConfigInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Capgemini\TechConfigurator\Model\ResourceModel\Config::class);
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
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        return $this->setData('name', $name);
    }

    /**
     * {@inheritDoc}
     */
    public function getFootnotes()
    {
        return $this->getData('footnotes');
    }

    /**
     * {@inheritDoc}
     */
    public function setFootnotes($footnotes)
    {
        return $this->setData('footnotes', $footnotes);
    }

    /**
     * {@inheritDoc}
     */
    public function getSkuParts()
    {
        return $this->getData('sku_parts');
    }

    /**
     * {@inheritDoc}
     */
    public function setSkuParts($skuParts)
    {
        return $this->setData('sku_parts', $skuParts);
    }

    /**
     * {@inheritDoc}
     */
    public function getExceptions()
    {
        return $this->getData('exceptions');
    }

    /**
     * {@inheritDoc}
     */
    public function setExceptions($exceptions)
    {
        return $this->setData('exceptions', $exceptions);
    }
}