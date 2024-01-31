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

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class RequisitionViewPlugin
 * @package Lyonscg\RequisitionList\Plugin
 */
class RequisitionViewPlugin
{

    /**
     * @param \Magento\RequisitionList\Controller\Requisition\View $subject
     * @param ResultInterface|ResponseInterface $result
     * @return ResultInterface|ResponseInterface
     */
    public function afterExecute($subject, $result)
    {
        $result->getConfig()->getTitle()->set(__('Quote View | Visual Comfort'));
        return $result;
    }
}
