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
 *
 * @method int getCustomerId()
 * @method int setCustomerId($customerId)
 */
interface WalletInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const WALLET_ID = 'wallet_id';

    const CUSTOMER_ID = 'customer_id';

    const CC_LAST4 = 'cc_last4';

    const CARD_NAME = 'card_name';

    const CARD_EXP_DATE = 'card_exp_date';

    const CREATED_AT = 'created_at';

    const PAYFABRIC_WALLET_ID = 'payfabric_wallet_id';

    const IS_DEFAULT = 'is_default';

    const CC_NICKNAME = 'cc_nickname';

    const CC_OWNERNAME = 'cc_ownername';

    /**
     * @return mixed
     */
    public function getWalletId();

    /**
     * @param $walletId
     * @return mixed
     */
    public function setWalletId($walletId);

    /**
     * @return mixed
     */
    public function getCcLast4();

    /**
     * @param $ccLast4
     * @return mixed
     */
    public function setCcLast4($ccLast4);

    /**
     * @return mixed
     */
    public function getCardName();

    /**
     * @param $cardName
     * @return mixed
     */
    public function setCardName($cardName);


    /**
     * @return mixed
     */
    public function getCardExpDate();

    /**
     * @param $cardName
     * @return mixed
     */
    public function setCardExpDate($cardName);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getPayfabricWalletId();

    /**
     * @param $payfabricWalletId
     * @return mixed
     */
    public function setPayfabricWalletId($payfabricWalletId);


    /**
     * @return boolean
     */
    public function getIsDefault();

    /**
     * @param $isDefault
     * @return mixed
     */
    public function setIsDefault($isDefault);


    /**
     * @return mixed
     */
    public function getCcNickname();

    /**
     * @param $ccNickname
     * @return mixed
     */
    public function setCcNickname($ccNickname);


    /**
     * @return mixed
     */
    public function getCcOwnername();

    /**
     * @param $ccOwnername
     * @return mixed
     */
    public function setCcOwnername($ccOwnername);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Capgemini\MyWallet\Api\Data\WalletExtensionInterface |null
     */
    public function getExtensionAttributes();

    /**
     * @param \Capgemini\MyWallet\Api\Data\WalletExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Capgemini\MyWallet\Api\Data\WalletExtensionInterface $extensionAttributes);
}
