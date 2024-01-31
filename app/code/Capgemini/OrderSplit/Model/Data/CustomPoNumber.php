<?php
/**
 * Capgemini_OrderSplit
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\OrderSplit\Model\Data;

use Capgemini\OrderSplit\Api\Data\CustomPoNumberInterface;

/**
 * Custom PO Number data model
 */
class CustomPoNumber extends \Magento\Framework\DataObject
    implements \Capgemini\OrderSplit\Api\Data\CustomPoNumberInterface, \JsonSerializable
{
    /**
     * @inheritDoc
     */
    public function getDivision(): ?string
    {
        return $this->getData('division');
    }

    /**
     * @inheritDoc
     */
    public function setDivision(string $division)
    {
        return $this->setData('division', $division);
    }

    /**
     * @inheritDoc
     */
    public function getPoNumber(): ?string
    {
        return $this->getData('po_number');
    }

    /**
     * @inheritDoc
     */
    public function setPoNumber(string $poNumber)
    {
        return $this->setData('po_number', $poNumber);
    }

    public function jsonSerialize(): mixed
    {
        return $this->getData();
    }
}
