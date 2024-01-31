<?php
/**
 * Wallet Interface
 *
 * @category  Lyons
 * @package   Capgemini_MyWallet
 * @author    Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Api\Data;

/**
 * @api
 * @since 100.0.2
 */
interface WalletSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Capgemini\Wallet\Api\Data\WalletInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Capgemini\Wallet\Api\Data\WalletInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
