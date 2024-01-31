<?php
/**
 * Capgemini_AmastyStoreLocator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\AmastyStoreLocator\Setup\Patch\Data;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Update location list template in configuration
 */
class UpdateListTemplate implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;
    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * Constructor
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $configWriter
    ) {

        $this->moduleDataSetup = $moduleDataSetup;
        $this->configWriter = $configWriter;
    }

    /**
     * {@inheritDoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $this->configWriter->save('amlocator/locator/visual_settings/store_list_template',<<<VALUE
{{name}}<br />
{{address}}<br />
{{city}}, {{state}}, {{zip}} <br />
{{phone}} <br />
<div class="direction-link">Get Directions</div>
VALUE
);

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritDoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAliases()
    {
        return [];
    }
}