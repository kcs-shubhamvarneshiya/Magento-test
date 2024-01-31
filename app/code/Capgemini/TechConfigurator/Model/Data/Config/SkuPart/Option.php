<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\Data\Config\SkuPart;

/**
 * Sku Part Option data model
 */
class Option extends \Magento\Framework\Model\AbstractModel
    implements \Capgemini\TechConfigurator\Api\Data\SkuPartOptionInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Capgemini\TechConfigurator\Model\ResourceModel\Config\SkuPart::class);
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
    public function getSkupartId()
    {
        return $this->getData('skupart_id');
    }

    /**
     * {@inheritDoc}
     */
    public function setSkupartId($skupartId)
    {
        return $this->setData('skupart_id', $skupartId);
    }

    /**
     * {@inheritDoc}
     */
    public function getCharacter()
    {
        return $this->getData('character');
    }

    /**
     * {@inheritDoc}
     */
    public function setCharacter($character)
    {
        return $this->setData('character', $character);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription($description)
    {
        return $this->setData('description', $description);
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
}
