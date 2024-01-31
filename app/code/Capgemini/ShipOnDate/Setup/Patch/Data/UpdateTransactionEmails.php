<?php

namespace Capgemini\ShipOnDate\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateTransactionEmails implements DataPatchInterface
{
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $emailsId = [44, 45];
        foreach ($emailsId as $emailId) {
            $select = $this->moduleDataSetup->getConnection()->select()->from(
                $this->moduleDataSetup->getTable('email_template')
            )->where('template_id = ?', (int)$emailId);

            $data = $this->moduleDataSetup->getConnection()->fetchRow($select);
            if (isset($data['template_text'])) {
                $replace = '<p>{{var order.shipping_description}}</p>';
                $newShippingInfo = '<p>{{var order.shipping_description}}</p>
                            {{if ship_on_date}} :
                            <strong>{{var ship_on_date}}</strong>
                            {{/if}}';
                $text = $data['template_text'];
                $newText = str_replace($replace, $newShippingInfo, $text);

                $this->moduleDataSetup->getConnection()->update(
                    $this->moduleDataSetup->getTable('email_template'),
                    ['template_text' => $newText],
                    ['template_id = ?' => (int)$emailId]
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
