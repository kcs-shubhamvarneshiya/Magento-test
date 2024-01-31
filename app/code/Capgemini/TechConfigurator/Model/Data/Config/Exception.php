<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\Data\Config;

/**
 * Exception data model
 */
class Exception extends \Magento\Framework\Model\AbstractModel
    implements \Capgemini\TechConfigurator\Api\Data\ExceptionInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception::class);
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
    public function getskupartName()
    {
        return $this->getData('skupart_name');
    }

    /**
     * {@inheritDoc}
     */
    public function setskupartName($skupartName)
    {
        return $this->setData('skupart_name', $skupartName);
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionCharacter()
    {
        return $this->getData('option_character');
    }

    /**
     * {@inheritDoc}
     */
    public function setOptionCharacter($optionCharacter)
    {
        return $this->setData('option_character', $optionCharacter);
    }

    /**
     * {@inheritDoc}
     */
    public function getConditions()
    {
        return $this->getData('conditions');
    }

    /**
     * {@inheritDoc}
     */
    public function setConditions($conditions)
    {
        return $this->setData('conditions', $conditions);
    }
}