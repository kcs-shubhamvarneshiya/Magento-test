<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Api\Data;

/**
 * Sku Part Option interface
 */
interface SkuPartOptionInterface
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
     * Skupart Id getter
     *
     * @return int
     */
    public function getSkupartId();

    /**
     * Skupart Id setter
     *
     * @param int $skupartId
     * @return $this
     */
    public function setSkupartId($skupartId);

    /**
     * Character getter
     *
     * @return string
     */
    public function getCharacter();

    /**
     * Character setter
     *
     * @param string $character
     * @return $this
     */
    public function setCharacter($character);

    /**
     * Description getter
     *
     * @return string
     */
    public function getDescription();

    /**
     * Description setter
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

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
}