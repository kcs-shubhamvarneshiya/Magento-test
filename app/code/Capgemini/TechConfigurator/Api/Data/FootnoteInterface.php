<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Api\Data;

/**
 * Footnote Interface
 */
interface FootnoteInterface
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
     * Config ID getter
     *
     * @return int
     */
    public function getConfigId();

    /**
     * Config ID setter
     *
     * @param int $configId
     * @return $this
     */
    public function setConfigId($configId);

    /**
     * Number getter
     *
     * @return string
     */
    public function getNumber();

    /**
     * Number setter
     *
     * @param string $number
     * @return $this
     */
    public function setNumber($number);

    /**
     * Footnote getter
     *
     * @return string
     */
    public function getFootnote();

    /**
     * Footnote setter
     *
     * @param string $footnote
     * @return $this
     */
    public function setFootnote($footnote);

}