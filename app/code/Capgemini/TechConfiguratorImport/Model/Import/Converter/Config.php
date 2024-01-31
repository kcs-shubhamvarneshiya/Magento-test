<?php
/**
 * Capgemini_TechConfiguratorImport
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfiguratorImport\Model\Import\Converter;

use Capgemini\TechConfigurator\Api\Data\ConfigInterface;
use Capgemini\TechConfigurator\Api\Data\ConfigInterfaceFactory;
use Capgemini\TechConfigurator\Api\Data\ExceptionConditionInterface;
use Capgemini\TechConfigurator\Api\Data\ExceptionInterface;
use Capgemini\TechConfigurator\Api\Data\ExceptionInterfaceFactory;
use Capgemini\TechConfigurator\Api\Data\ExceptionConditionInterfaceFactory;
use Capgemini\TechConfigurator\Api\Data\FootnoteInterface;
use Capgemini\TechConfigurator\Api\Data\FootnoteInterfaceFactory;
use Capgemini\TechConfigurator\Api\Data\SkuPartInterface;
use Capgemini\TechConfigurator\Api\Data\SkuPartInterfaceFactory;
use Capgemini\TechConfigurator\Api\Data\SkuPartOptionInterface;
use Capgemini\TechConfigurator\Api\Data\SkuPartOptionInterfaceFactory;

/**
 * Config converter. For converting import data array into Magento object
 */
class Config
{
    /**
     * @var ConfigInterfaceFactory
     */
    protected $configFactory;
    /**
     * @var ExceptionInterfaceFactory
     */
    protected $exceptionFactory;
    /**
     * @var FootnoteInterfaceFactory
     */
    protected $footnoteFactory;
    /**
     * @var SkuPartInterfaceFactory
     */
    protected $skuPartFactory;
    /**
     * @var SkuPartOptionInterfaceFactory
     */
    protected $skuPartOptionFactory;

    /**
     * @var ExceptionConditionInterfaceFactory
     */
    protected $exceptionConditionFactory;

    /**
     * @param ConfigInterfaceFactory $configFactory
     */
    public function __construct(
        ConfigInterfaceFactory $configFactory,
        ExceptionInterfaceFactory $exceptionFactory,
        ExceptionConditionInterfaceFactory $exceptionConditionFactory,
        FootnoteInterfaceFactory $footnoteFactory,
        SkuPartInterfaceFactory $skuPartFactory,
        SkuPartOptionInterfaceFactory $skuPartOptionFactory
    ) {
        $this->configFactory = $configFactory;
        $this->exceptionFactory = $exceptionFactory;
        $this->footnoteFactory = $footnoteFactory;
        $this->skuPartFactory = $skuPartFactory;
        $this->skuPartOptionFactory = $skuPartOptionFactory;
        $this->exceptionConditionFactory = $exceptionConditionFactory;
    }

    /**
     * Convert import array into config object
     *
     * @param array $data
     * @return ConfigInterface
     */
    public function convert($configData)
    {
        $config = $this->configFactory->create();
        $config->setName($configData['Name']??null);
        $config->setFootnotes($this->getFootnotes($configData));
        $config->setSkuParts($this->getSkuParts($configData));
        $config->setExceptions($this->getExceptions($configData));
        return $config;
    }

    /**
     * Convert footnotes
     *
     * @param array $configData
     * @return FootnoteInterface[]
     */
    protected function getFootnotes($configData)
    {
        $footnotes = [];
        if (isset($configData['Footnotes'])) {
            foreach ($configData['Footnotes'] as $footnoteData) {
                if (isset($footnoteData['Footnote'])) {
                    $footnoteData = $footnoteData['Footnote'];
                    $footnote = $this->footnoteFactory->create();
                    $footnote->setFootnote($footnoteData['Text']??null);
                    $footnote->setNumber($footnoteData['Number']??null);
                    $footnotes[] = $footnote;
                }
            }
        }
        return $footnotes;
    }

    /**
     * Convert exceptions
     *
     * @param $configData
     * @return ExceptionInterface[]
     */
    protected function getExceptions($configData)
    {
        $extensions = [];
        if (isset($configData['Exceptions']) && count($configData['Exceptions']) > 0) {
            $exceptionsData = $configData['Exceptions'];
            foreach ($exceptionsData as $exceptionData) {
                if (isset($exceptionData['Exception']) && count($exceptionData['Exception'])) {
                    $exceptionData = $exceptionData['Exception'];
                    $exceptionParams = array_pop($exceptionData);
                    $exception = $this->exceptionFactory->create();
                    $exception->setSkupartName($exceptionParams['SkuPart']??null);
                    $exception->setOptionCharacter($exceptionParams['AttributeChar']??null);
                    $exception->setConditions($this->getExceptionConditions($exceptionData));
                    $extensions[] = $exception;
                }
            }
        }
        return $extensions;
    }

    /**
     * Convert conditions
     *
     * @param array $conditions
     * @return ExceptionConditionInterface[]
     */
    protected function getExceptionConditions($conditionsData)
    {
        $conditions = [];
        foreach ($conditionsData as $conditionData) {
            $condition = $this->exceptionConditionFactory->create();
            $condition->setSkupartName($conditionData['SkuPart']??null);
            $condition->setOptionCharacter($conditionData['AttributeChar']??null);
            $conditions[] = $condition;
        }
        return $conditions;
    }

    /**
     * Convert sku parts
     *
     * @param array $configData
     * @return SkuPartInterface[]
     */
    protected function getSkuParts($configData)
    {
        $skuParts = [];
        if (isset($configData['SkuParts'])) {
            foreach ($configData['SkuParts'] as $skuPartData) {
                if (isset($skuPartData['SkuPart'])) {
                    $skuPartData = $skuPartData['SkuPart'];
                    $skuPart = $this->skuPartFactory->create();
                    $skuPart->setName($skuPartData['Name']??null);
                    $skuPart->setSort($skuPartData['Order']??null);
                    $skuPart->setOptional($skuPartData['Optional']??null);
                    $skuPart->setHelpText($skuPartData['HelpText']??null);
                    $skuPart->setFootnotes($skuPartData['Footnotes']??null);
                    $skuPart->setType($skuPartData['Type']??'option');
                    $skuPart->setValidation($skuPartData['Validation']??null);
                    $skuPart->setOptions($this->getSkuPartOptions($skuPartData));
                    $skuParts[] = $skuPart;
                }
            }
        }
        return $skuParts;
    }

    /**
     * Convert sku part options
     *
     * @param array $skuPartData
     * @return SkuPartOptionInterface[]
     */
    protected function getSkuPartOptions($skuPartData)
    {
        $options = [];
        if (isset($skuPartData['Options'])) {
            foreach ($skuPartData['Options'] as $optionData) {
                if (isset ($optionData['Option'])) {
                    $optionData = $optionData['Option'];
                    $option = $this->skuPartOptionFactory->create();
                    $option->setCharacter($optionData['AttributeChar']??null);
                    $option->setDescription($optionData['AttributeDesc']??null);
                    $option->setFootnotes($optionData['Footnotes']??null);
                    $options[] = $option;
                }
            }
        }
        return $options;
    }
}