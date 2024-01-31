<?php

namespace Lyonscg\Affirm\Model\Ui;

use Astound\Affirm\Model\Ui\ConfigProvider as Orig;

class ConfigProvider extends Orig
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'delayedShippingMessage' => $this->config->getValue('delayed_shipping_message')
                ]
            ]
        ];
    }
}
