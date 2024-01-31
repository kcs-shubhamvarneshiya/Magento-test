<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\Plugin;

use Magento\Framework\UrlInterface;
use Magento\RequisitionList\Ui\Component\Listing\Column\Actions;

/**
 * Class ColumnActionsPlugin
 * @package Lyonscg\RequisitionList\Plugin
 */
class ColumnActionsPlugin
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * ColumnActionsPlugin constructor.
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param Actions $subject
     * @param array $result
     * @return mixed
     */
    public function afterPrepareDataSource($subject, $result)
    {
        if (isset($result['data']['items'])) {
            foreach ($result['data']['items'] as &$item) {
                $item['actions'] = [
                    'view' => $item['action']['view'],
                    'create_order' => [
                        'href' => $this->urlBuilder->getUrl(
                            'requisition_list/requisition/createorder',
                            ['requisition_id' => $item['entity_id']]
                        ),
                        'label'   => __('Add to Cart'),
                        'confirm' => [
                            'title'   => __('Are you sure you want to add to cart?'),
                            'message' => __('Once you move a quote to the cart to submit your order, the quote will no longer exist.'),
                            'cartNotEmptyTitle' => __('You already have items in your cart'),
                            'urlReplace' => $this->urlBuilder->getUrl(
                                'requisition_list/requisition/createorder',
                                ['requisition_id' => $item['entity_id'], 'action' => 'replace']
                            ),
                            'urlMerge' => $this->urlBuilder->getUrl(
                                'requisition_list/requisition/createorder',
                                ['requisition_id' => $item['entity_id'], 'action' => 'merge']
                            ),
                            'urlCreateOrder' => $this->urlBuilder->getUrl(
                                'requisition_list/requisition/createorder',
                                ['requisition_id' => $item['entity_id']]
                            )
                        ]
                    ]
                ];
            }
        }
        return $result;
    }
}
