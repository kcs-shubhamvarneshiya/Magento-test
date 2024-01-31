<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Api\OrderRequest;

use Capgemini\RequestToOrder\Api\Data\OrderRequest\ItemInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ItemRepositoryInterface
{
    /**
     * @return ItemInterface
     */
    public function create(): ?ItemInterface;

    /**
     * Save
     *
     * @param ItemInterface $requestItem
     * @return ItemInterface
     */
    public function save(ItemInterface $requestItem): ItemInterface;

    /**
     * Get by id
     *
     * @param int $id
     * @return ItemInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ?ItemInterface;

    /**
     * Delete
     *
     * @param ItemInterface $requestItem
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(ItemInterface $requestItem): bool;
}
