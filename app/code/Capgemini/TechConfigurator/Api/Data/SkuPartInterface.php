<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Api\Data;

/**
 * Sku Part Interface
 */
interface SkuPartInterface
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
     * Name getter
     *
     * @return string
     */
    public function getName();

    /**
     * Name setter
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Type getter
     *
     * @return string
     */
    public function getType();

    /**
     * Type setter
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Sort getter
     *
     * @return int
     */
    public function getSort();

    /**
     * Sort setter
     *
     * @param int $sort
     * @return $this
     */
    public function setSort($sort);

    /**
     * Optional getter
     *
     * @return string
     */
    public function getOptional();

    /**
     * Optional setter
     *
     * @param string $optional
     * @return $this
     */
    public function setOptional($optional);

    /**
     * Help Text getter
     *
     * @return string
     */
    public function getHelpText();

    /**
     * Help Text setter
     *
     * @param string $helpText
     * @return $this
     */
    public function setHelpText($helpText);

    /**
     * Footnotes getter
     *
     * @return string
     */
    public function getFootnotes();

    /**
     * Footnotes setter
     *
     * @param string $footnotes
     * @return $this
     */
    public function setFootnotes($footnotes);

    /**
     * Validation getter
     *
     * @return string
     */
    public function getValidation();

    /**
     * Validation setter
     *
     * @param string $validation
     * @return $this
     */
    public function setValidation($validation);

    /**
     * Options getter
     *
     * @return SkuPartOptionInterface[]
     */
    public function getOptions();

    /**
     * Options setter
     *
     * @parem SkuPartOptionInterface[] $options
     * @return $this
     */
    public function setOptions($options);
}