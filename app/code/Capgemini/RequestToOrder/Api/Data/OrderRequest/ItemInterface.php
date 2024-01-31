<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Api\Data\OrderRequest;

interface ItemInterface
{
    public const REQUEST_ITEM_TABLE = 'capgemini_rto_item';

    /**
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const ID = 'rto_item_id';
    public const RTO_ID = 'rto_id';
    public const REQUEST = 'request';
    public const PRODUCT_ID = 'product_id';
    public const SKU = 'sku';
    public const NAME = 'name';
    public const PRICE = 'price';
    public const QTY = 'qty';

    /**
     * @return string|null
     */
    public function getRtoItemId(): ?int;

    /**
     * @param string|null $rtoItemId
     * @return mixed
     */
    public function setRtoItemId(?string $rtoItemId);

    /**
     * @return int|null
     */
    public function getRtoId(): ?int;

    /**
     * @param string|null $rtoId
     * @return mixed
     */
    public function setRtoId(?string $rtoId);

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param int $productId
     * @return mixed
     */
    public function setProductId(int $productId);

    /**
     * @return null|string
     */
    public function getSku(): ?string;

    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void;

    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty): void;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void;
}
