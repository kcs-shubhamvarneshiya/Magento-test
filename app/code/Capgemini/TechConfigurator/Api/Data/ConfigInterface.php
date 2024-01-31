<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Api\Data;

/**
 * Product Configurator Config interface
 */
interface ConfigInterface
{
    /**
     * ID getter
     *
     * @return int
     */
    public function getId();

    /**
     * ID setter
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

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
     * Footnotes getter
     *
     * @return FootnoteInterface[]
     */
    public function getFootnotes();

    /**
     * Footnotes setter
     *
     * @param FootnoteInterface[] $footnotes
     * @return $this
     */
    public function setFootnotes($footnotes);

    /**
     * Sku Parts getter
     *
     * @return SkuPartInterface[]
     */
    public function getSkuParts();

    /**
     * Sku Parts setter
     *
     * @param SkuPartInterface[] $skuParts
     * @return $this
     */
    public function setSkuParts($skuParts);

    /**
     * Exceptions getter
     *
     * @return ExceptionInterface[]
     */
    public function getExceptions();

    /**
     * Exceptions setter
     *
     * @param ExceptionInterface[] $exceptions
     * @return $this
     */
    public function setExceptions($exceptions);
}