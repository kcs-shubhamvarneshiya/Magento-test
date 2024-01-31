<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Model\OrderRequest;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationInterface;
use Magento\Quote\Model\Quote;

class Relation implements RelationInterface
{
    /**
     * Process object relations
     *
     * @param AbstractModel $object
     * @return void
     */
    public function processRelation(AbstractModel $object)
    {
        /**
         * @var $object Quote
         */
        if ($object->itemsCollectionWasSet()) {
            $object->getItemsCollection()->save();
        }
    }
}
