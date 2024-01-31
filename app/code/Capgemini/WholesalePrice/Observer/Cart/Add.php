<?php
/**
 * Capgemini_WholesalePrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WholesalePrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\WholesalePrice\Observer\Cart;

use Capgemini\WholesalePrice\Model\Price;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Add implements ObserverInterface
{
    /**
     * @var Price
     */
    protected Price $priceModel;

    /**
     * @param Price $priceModel
     */
    public function __construct(
        Price $priceModel
    ) {
        $this->priceModel = $priceModel;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $item = $observer->getEvent()->getData('quote_item');
        $price = $this->priceModel->getCustomerPrice($item->getProduct());
        if ($price) {
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
        }
    }
}
