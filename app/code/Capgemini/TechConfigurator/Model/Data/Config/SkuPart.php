<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\Data\Config;

/**
 * Sku Part data model
 */
class SkuPart extends \Magento\Framework\Model\AbstractModel
    implements \Capgemini\TechConfigurator\Api\Data\SkuPartInterface
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
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        return $this->setData('type', $type);
    }

    /**
     * {@inheritDoc}
     */
    public function getSort()
    {
        return $this->getData('sort');
    }

    /**
     * {@inheritDoc}
     */
    public function setSort($sort)
    {
        return $this->setData('sort', $sort);
    }

    /**
     * {@inheritDoc}
     */
    public function getOptional()
    {
        return $this->getData('optional');
    }

    /**
     * {@inheritDoc}
     */
    public function setOptional($optional)
    {
        return $this->setData('optional', $optional);
    }

    /**
     * {@inheritDoc}
     */
    public function getHelpText()
    {
        return $this->getData('help_text');
    }

    /**
     * {@inheritDoc}
     */
    public function setHelpText($helpText)
    {
        return $this->setData('help_text', $helpText);
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
    public function getValidation()
    {
        return $this->getData('validation');
    }

    /**
     * {@inheritDoc}
     */
    public function setValidation($validation)
    {
        return $this->setData('validation', $validation);
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->getData('options');
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions($options)
    {
        return $this->setData('options', $options);
    }
}
