<?php
/**
 * Capgemini_TechConfiguratorImport
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfiguratorImport\Model\Import;

use Capgemini\TechConfigurator\Api\Data\ConfigInterfaceFactory;
use Capgemini\TechConfiguratorImport\Model\Import\Converter\Config;
use Capgemini\TechConfigurator\Model\ResourceModel\Config as ConfigResource;
use Magento\ImportExport\Model\Import;

/**
 * Product configurator import processor
 */
class Processor
{
    /**
     * @var Config
     */
    protected $converter;
    protected ConfigResource $configResource;
    protected ConfigInterfaceFactory $configFactory;

    /**
     * @param Config $converter
     */
    public function __construct(
        Config $converter,
        ConfigResource $configResource,
        ConfigInterfaceFactory $configFactory
    ) {
        $this->converter = $converter;
        $this->configResource = $configResource;
        $this->configFactory = $configFactory;
    }

    /**
     * @param $data
     * @param string $behaviour
     * @return array ['deleted' => n, 'created' => m]
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function import($data, $behaviour = Import::BEHAVIOR_REPLACE)
    {
        $result = ['deleted' => 0, 'created' => 0];
        $config = $this->converter->convert($data);

        $oldConfig = $this->configFactory->create();
        $this->configResource->load($oldConfig, $config->getName(), 'name');
        if ($oldConfig->getId()) {
            $this->configResource->delete($oldConfig);
            $result['deleted']++;
        }
        if ($behaviour == Import::BEHAVIOR_REPLACE) {
            $this->configResource->save($config);
            $result['created']++;
        }
        return $result;
    }
}
