<?php
/**
 * Wallet model
 *
 * @category  Capgemini
 * @package   Capgemini_MyWallet
 * @copyright 2020 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\MyWallet\Model;

use Capgemini\MyWallet\Api\Data\WalletInterface;

class Wallet extends \Magento\Framework\Model\AbstractExtensibleModel implements WalletInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Capgemini\MyWallet\Model\ResourceModel\Wallet');
    }

    /**
     * @inheritdoc
     */
    public function getWalletId()
    {
        return $this->getData(self::WALLET_ID);
    }

    /**
     * @inheritdoc
     */
    public function setWalletId($walletId)
    {
        $this->setData(self::WALLET_ID, $walletId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCcLast4()
    {
        return $this->getData(self::CC_LAST4);
    }

    /**
     * @inheritdoc
     */
    public function setCcLast4($ccLast4)
    {
        $this->setData(self::CC_LAST4, $ccLast4);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCardName()
    {
        return $this->getData(self::CARD_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setCardName($cardName)
    {
        $this->setData(self::CARD_NAME, $cardName);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCardExpDate()
    {
        return $this->getData(self::CARD_EXP_DATE);
    }

    /**
     * @inheritdoc
     */
    public function setCardExpDate($cardExpDate)
    {
        $this->setData(self::CARD_EXP_DATE, $cardExpDate);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setCustomerId($customerId)
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt($createdAt)
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }


    /**
     * @return string
     */
    public function getPayfabricWalletId()
    {
        return $this->getData(self::PAYFABRIC_WALLET_ID);
    }

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setPayfabricWalletId($payfabricWalletId)
    {
        $this->setData(self::PAYFABRIC_WALLET_ID, $payfabricWalletId);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->getData(self::IS_DEFAULT);
    }

    /**
     * @param $isDefault
     * @return mixed
     */
    public function setIsDefault($isDefault)
    {
        $this->setData(self::IS_DEFAULT, $isDefault);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCcNickname()
    {
        return $this->getData(self::CC_NICKNAME);
    }

    /**
     * @param $isDefault
     * @return mixed
     */
    public function setCcNickname($ccNickname)
    {
        $this->setData(self::CC_NICKNAME, $ccNickname);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCcOwnername()
    {
        return $this->getData(self::CC_OWNERNAME);
    }

    /**
     * @param $isDefault
     * @return mixed
     */
    public function setCcOwnername($ccOwnername)
    {
        $this->setData(self::CC_OWNERNAME, $ccOwnername);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(\Capgemini\MyWallet\Api\Data\WalletExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
