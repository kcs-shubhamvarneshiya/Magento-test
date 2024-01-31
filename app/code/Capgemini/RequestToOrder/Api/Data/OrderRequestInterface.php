<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Api\Data;

use Capgemini\RequestToOrder\Api\Data\OrderRequest\ItemInterface;

interface OrderRequestInterface
{
    public const REQUEST_TABLE = 'capgemini_rto';
    public const STATUS_ENABLE = 1;
    public const STATUS_DISABLE = 2;

    /**
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const ID = 'rto_id';
    public const CUSTOMER_ID = 'customer_id';
    public const STATUS = 'status';
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const VC_ACCOUNT = 'vc_account';
    public const TECH_ACCOUNT = 'tech_account';
    public const GL_ACCOUNT = 'gl_account';
    public const ITEMS = 'items';
    public const ITEMS_COLLECTION = 'items_collection';
    public const COMMENTS = 'comments';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return string|null
     */
    public function getRtoId(): ?string;

    /**
     * @param string|null $rtoId
     * @return mixed
     */
    public function setRtoId(?string $rtoId);

    /**
     * @return int
     */
    public function getCustomerId(): ?int;

    /**
     * @param null|int $customerId
     * @return void
     */
    public function setCustomerId(?int $customerId): void;

    /**
     * @return int
     */
    public function getStatus(): ?int;

    /**
     * @param null|int $status
     * @return void
     */
    public function setStatus(?int $status): void;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @param null|string $name
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * @return string
     */
    public function getEmail(): ?string;

    /**
     * @param null|string $email
     * @return void
     */
    public function setEmail(?string $email): void;

    /**
     * @return string
     */
    public function getPhone(): ?string;

    /**
     * @param null|string $phone
     * @return void
     */
    public function setPhone(?string $phone): void;

    /**
     * @return string
     */
    public function getVcAccount(): ?string;

    /**
     * @param null|string $vc_account
     * @return void
     */
    public function setVcAccount(?string $vc_account): void;

    /**
     * @return string
     */
    public function getTechAccount(): ?string;

    /**
     * @param null|string $tech_account
     * @return void
     */
    public function setTechAccount(?string $tech_account): void;

    /**
     * @return string
     */
    public function getGlAccount(): ?string;

    /**
     * @param null|string $gl_account
     * @return void
     */
    public function setGlAccount(?string $gl_account): void;

    /**
     * @return string
     */
    public function getComments(): ?string;

    /**
     * @param null|string $comments
     * @return mixed
     */
    public function setComments(?string $comments);

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string|null $createdAt
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;

    /**
     * @param ItemInterface $item
     * @return void
     */
    public function addItem(ItemInterface $item): void;
}
