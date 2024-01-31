<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\Data\Config\Exception;

/**
 * Exception Condition data model
 */
class Condition extends \Magento\Framework\Model\AbstractModel
    implements \Capgemini\TechConfigurator\Api\Data\ExceptionConditionInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception\Condition::class);
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
    public function getExceptionId()
    {
        return $this->getData('exception_id');
    }

    /**
     * {@inheritDoc}
     */
    public function setExceptionId($exceptionId)
    {
        return $this->setData('exception_id', $exceptionId);
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
}