<?php

namespace Capgemini\TechConfigurator\Model;

use Capgemini\TechConfigurator\Api\Data\ConfigInterface;
use Capgemini\TechConfigurator\Api\Data\ConfigInterfaceFactory;
use Capgemini\TechConfigurator\Model\ResourceModel\Config;
use \Capgemini\TechConfigurator\Model\ResourceModel\Config\SkuPart\CollectionFactory as SkuPartCollectionFactory;
use \Capgemini\TechConfigurator\Model\ResourceModel\Config\SkuPart\Option\CollectionFactory as OptionCollectionFactory;
use \Capgemini\TechConfigurator\Model\ResourceModel\Config\Footnote\CollectionFactory as FootnoteCollectionFactory;
use \Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception\CollectionFactory as ExceptionCollectionFactory;
use \Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception\Condition\CollectionFactory as ConditionCollectionFactory;

/**
 * Configurator repository
 */
class ConfigRepository
{
    /**
     * @var ConfigInterfaceFactory
     */
    protected $configFactory;
    /**
     * @var Config
     */
    protected Config $configResource;
    /**
     * @var SkuPartCollectionFactory
     */
    protected $skuPartCollectionFactory;
    /**
     * @var OptionCollectionFactory
     */
    protected $optionCollectionFactory;
    /**
     * @var FootnoteCollectionFactory
     */
    protected $footnoteCollectionFactory;
    /**
     * @var ExceptionCollectionFactory
     */
    protected $exceptionCollectionFactory;

    /**
     * @var ConditionCollectionFactory
     */
    protected $conditionCollectionFactory;
    /**
     * @var array
     */
    protected $loadedByName = [];

    /**
     * Constructor
     *
     * @param ConfigInterfaceFactory $configFactory
     * @param Config $configResource
     * @param SkuPartCollectionFactory $skuPartCollectionFactory
     * @param OptionCollectionFactory $optionCollectionFactory
     * @param FootnoteCollectionFactory $footnoteCollectionFactory
     * @param ExceptionCollectionFactory $exceptionCollectionFactory
     * @param ConditionCollectionFactory $conditionCollectionFactory
     */
    public function __construct(
        ConfigInterfaceFactory $configFactory,
        Config $configResource,
        SkuPartCollectionFactory $skuPartCollectionFactory,
        OptionCollectionFactory $optionCollectionFactory,
        FootnoteCollectionFactory $footnoteCollectionFactory,
        ExceptionCollectionFactory $exceptionCollectionFactory,
        ConditionCollectionFactory $conditionCollectionFactory,
    ) {
        $this->configFactory = $configFactory;
        $this->configResource = $configResource;
        $this->skuPartCollectionFactory = $skuPartCollectionFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->footnoteCollectionFactory = $footnoteCollectionFactory;
        $this->exceptionCollectionFactory = $exceptionCollectionFactory;
        $this->conditionCollectionFactory = $conditionCollectionFactory;
    }

    /**
     * Get configurator by name
     *
     * @param string $name configurator name
     * @param bool $forceLoad force reload from database
     * @return ConfigInterface
     */
    public function getByName($name, $forceLoad = false)
    {
        if ($forceLoad || !isset($this->loadedByName[$name])) {
            $config = $this->configFactory->create();
            $this->configResource->load($config, $name, 'name');
            if ($config->getId()) {
                $this->loadRelatedObjects($config);
            }
            if ($forceLoad) {
                return $config;
            } else {
                $this->loadedByName[$name] = $config;
            }
        }
        return $this->loadedByName[$name];
    }

    /**
     * @param ConfigInterface $config
     */
    protected function loadRelatedObjects(ConfigInterface $config)
    {
        $this->loadSkuParts($config);
        $this->loadFootnotes($config);
        $this->loadExceptions($config);
    }

    /**
     * @param ConfigInterface $config
     */
    protected function loadSkuParts(ConfigInterface $config)
    {
        $collection = $this->skuPartCollectionFactory->create();
        $collection->addFieldToFilter('config_id', $config->getId());
        $config->setSkuParts($collection->getItems());
        $this->loadOptions($config);
    }

    /**
     * @param ConfigInterface $config
     */
    protected function loadFootnotes(ConfigInterface $config)
    {
        $collection = $this->footnoteCollectionFactory->create();
        $collection->addFieldToFilter('config_id', $config->getId());
        $config->setFootnotes($collection->getItems());
    }

    /**
     * @param ConfigInterface $config
     */
    protected function loadExceptions(ConfigInterface $config)
    {
        $collection = $this->exceptionCollectionFactory->create();
        $collection->addFieldToFilter('config_id', $config->getId());
        $config->setExceptions($collection->getItems());
        $this->loadConditions($config);
    }

    /**
     * @param ConfigInterface $config
     */
    protected function loadOptions(ConfigInterface $config)
    {
        $skuPartIds = [];
        $skuPartsById = [];
        foreach ($config->getSkuParts() as $skuPart)
        {
            $skuPartIds[] = $skuPart->getId();
            $skuPartsById[$skuPart->getId()] = $skuPart;
        }
        if (count($skuPartIds) > 0) {
            $collection = $this->optionCollectionFactory->create();
            $collection->addFieldToFilter('skupart_id', ['in' => $skuPartIds]);

            $skuPartOptions = [];
            foreach ($collection->getItems() as $option) {
                $skuPartOptions[$option->getSkupartId()][] = $option;
            }
            foreach ($skuPartOptions as $id => $options) {
                $skuPartsById[$id]->setOptions($options);
            }
        }
    }

    /**
     * @param ConfigInterface $config
     */
    protected function loadConditions(ConfigInterface $config)
    {
        $exceptionIds = [];
        $exceptionsById = [];
        foreach ($config->getExceptions() as $exception)
        {
            $exceptionIds[] = $exception->getId();
            $exceptionsById[$exception->getId()] = $exception;
        }
        if (count($exceptionIds) > 0) {
            $collection = $this->conditionCollectionFactory->create();
            $collection->addFieldToFilter('exception_id', ['in' => $exceptionIds]);

            $exceptionConditions = [];
            foreach ($collection->getItems() as $condition) {
                $exceptionConditions[$condition->getExceptionId()][] = $condition;
            }
            foreach ($exceptionConditions as $id => $conditions) {
                $exceptionsById[$id]->setConditions($conditions);
            }
        }
    }
}