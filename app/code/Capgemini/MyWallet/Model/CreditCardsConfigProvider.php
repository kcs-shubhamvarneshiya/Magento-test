<?php
namespace Capgemini\MyWallet\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Capgemini\MyWallet\Api\WalletRepositoryInterface;
use Capgemini\MyWallet\Helper\Wallet as WalletHelper;
use Capgemini\MyWallet\Model\WalletApi;
use Magento\Framework\Serialize\Serializer\Json as jsonHelper;

/**
 * Class CreditCardsConfigProvider
 * @package Capgemini\MyWallet\Model
 */
class CreditCardsConfigProvider implements ConfigProviderInterface
{
    /**
     * @var WalletRepositoryInterface
     */
    protected $walletRepository;

    /**
     * @var WalletHelper
     */
    protected $walletHelper;

    /**
     * @var jsonHelper
     */
    protected $jsonHelper;

    protected $walletApi;

    /**
     * CreditCardsConfigProvider constructor.
     * @param WalletRepositoryInterface $walletRepository
     * @param WalletHelper $walletHelper
     * @param jsonHelper $jsonHelper
     */
    public function __construct(
        WalletRepositoryInterface $walletRepository,
        WalletHelper $walletHelper,
        jsonHelper $jsonHelper,
        WalletApi $walletApi
    )
    {
        $this->walletRepository = $walletRepository;
        $this->walletHelper = $walletHelper;
        $this->jsonHelper = $jsonHelper;
        $this->walletApi = $walletApi;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        // NOTE - this is the salespad customer number
        $customerId = $this->walletHelper->getCustomerId();
        if ($customerId) {
            $wallets = $this->_getWallets($customerId);
            if (count($wallets) > 0) {
                foreach ($wallets as $wallet) {
                    $expDate = $wallet->getCardExpDate();
                    $walletCollectionCollectionArray[] = array('nickname' => $wallet->getCcNickname(),
                        'wallet_id' => $wallet->getWalletId(),
                        'cc_last4' => $wallet->getCcLast4(),
                        'card_name' => $wallet->getCardName(),
                        'exp_date' => substr($expDate, 0, 2) . '/' . '20' . substr($expDate, 2, 2));
                }
                $config['customerCreditCards'] = $walletCollectionCollectionArray;
            }

            return $config;
        }

        return $config;
    }

    /**
     * Note customerId refers to the SalesPad Customer_Num, not the entity_id of the Magento Customer
     * @param $customerId
     * @return array
     */
    protected function _getWallets($customerId)
    {
        $existingWallets = $this->walletApi->retrieveCardsByCustomerId($customerId);
        if ($existingWallets === false) {
            $existingWallets = [];
        }
        /** @var \Capgemini\MyWallet\Model\ResourceModel\Wallet\Collection $walletCollection */
        $walletCollection = $this->walletRepository->search($customerId);

        if (!is_array($existingWallets)) {
            // error getting wallets from Payfabric, just return what we have here
            // if there is an error getting wallets, there will probably be one trying to place the order
            // so there is no reason to delete anything yet
            return $walletCollection->getItems();
        }
        $validWallets = [];
        foreach ($walletCollection as $wallet) {
            $isCurrentMagentoWalletValid = false;
            foreach ($existingWallets as $existingWallet) {
                if ($wallet->getPayfabricWalletId() == $existingWallet['ID']) {
                    $validWallets[] = $wallet;
                    $isCurrentMagentoWalletValid = true;
                    break;
                }
            }
            if ($isCurrentMagentoWalletValid === false) {
                // wallet not found in PayFabric
                try {
                    $this->walletRepository->delete($customerId, $wallet);
                } catch (\Exception $e) {
                    // failed to delete wallet, just ignore it
                }
            }
        }
        return $validWallets;
    }
}
