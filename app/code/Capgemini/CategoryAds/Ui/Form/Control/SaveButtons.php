<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Ui\Form\Control;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButtons extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData() : array
    {
        return [
            'label'      => __('Save'),
            'class'      => 'primary',
            'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
            'sort_order' => 10,
            'options'    => [
                [
                    'id'             => 'save_continue',
                    'label'          => __('Save & Continue'),
                    'default'        => true,
                    'data_attribute' => [
                        'mage-init' => [
                            'buttonAdapter' => [
                                'actions' => [
                                    [
                                        'targetName' => 'categoryads_ad_form.categoryads_ad_form',
                                        'actionName' => 'save',
                                        'params'     => [true, [
                                            'back' => 'continue',
                                        ]],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id'             => 'save_send',
                    'label'          => __('Save & Send'),
                    'data_attribute' => [
                        'mage-init' => [
                            'buttonAdapter' => [
                                'actions' => [
                                    [
                                        'targetName' => 'categoryads_ad_form.categoryads_ad_form',
                                        'actionName' => 'save',
                                        'params'     => [true, [
                                            'back' => 'send',
                                        ]],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
