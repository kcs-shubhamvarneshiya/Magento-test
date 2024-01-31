<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Api;

use Capgemini\RequestToOrder\Api\Data\OrderRequestInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

interface OrderRequestRepositoryInterface
{
    /**
     * @return OrderRequestInterface
     */
    public function create(): ?OrderRequestInterface;

    /**
     * Save
     *
     * @param OrderRequestInterface $request
     * @return OrderRequestInterface
     */
    public function save(OrderRequestInterface $request): OrderRequestInterface;

    /**
     * Get by id
     *
     * @param int $id
     * @return OrderRequestInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ?OrderRequestInterface;

    /**
     * Delete
     *
     * @param OrderRequestInterface $request
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(OrderRequestInterface $request): bool;

    /**
     * @param int $customerId
     * @param bool $active
     * @return null|OrderRequestInterface
     */
    public function getByCustomerId(int $customerId, bool $active): ?OrderRequestInterface;
}
