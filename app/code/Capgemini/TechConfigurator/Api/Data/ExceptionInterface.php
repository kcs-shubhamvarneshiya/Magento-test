<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Api\Data;

/**
 * Exception interface
 */
interface ExceptionInterface
{
    /**
     * Id getter
     *
     * @return int
     */
    public function getId();

    /**
     * Id setter
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Config Id getter
     *
     * @return int
     */
    public function getConfigId();

    /**
     * Config Id setter
     *
     * @param int $configId
     * @return $this
     */
    public function setConfigId($configId);

    /**
     * Skupart Name getter
     *
     * @return string
     */
    public function getSkupartName();

    /**
     * Except Skupart Name setter
     *
     * @param string $skupartName
     * @return $this
     */
    public function setSkupartName($skupartName);

    /**
     * Option Character getter
     *
     * @return string
     */
    public function getOptionCharacter();

    /**
     * Option Character setter
     *
     * @param string $optionCharacter
     * @return $this
     */
    public function setOptionCharacter($optionCharacter);

    /**
     * @return ExceptionConditionInterface[]
     */
    public function getConditions();

    /**
     * @param ExceptionConditionInterface[] $conditions
     * @return $this
     */
    public function setConditions($conditions);
}