<?php
/**
 * Capgemini_CompanyType
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_CompanyType
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\CompanyType\Observer;

use Capgemini\CompanyType\Helper\ReportingBrand;
use Capgemini\CompanyType\Model\Config;
use Capgemini\CompanyType\Model\Product\PurchasePermission;
use Capgemini\RequestToOrder\Helper\Data as RequestToOrderHelper;
use Magento\Checkout\Helper\Cart;
use Magento\Checkout\Model\Session;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;

class ValidateItems implements ObserverInterface
{
    /**
     * @var RedirectInterface
     */
    protected RedirectInterface $redirect;
    /**
     * @var Session
     */
    private Session $checkoutSession;
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $_messageManager;
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;
    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @var PurchasePermission
     */
    protected PurchasePermission $purchasePermissionValidator;

    /**
     * @var ReportingBrand
     */
    protected ReportingBrand $reportingBrandHelper;

    /**
     * @var Cart
     */
    protected Cart $cartHelper;

    /**
     * @var RequestToOrderHelper
     */
    protected RequestToOrderHelper $requestToOrderHelper;

    /**
     * @param Session $checkoutSession
     * @param ManagerInterface $messageManager
     * @param CustomerSession $customerSession
     * @param Config $companyTypeConfig
     * @param PurchasePermission $purchasePermissionValidator
     * @param ReportingBrand $reportingBrandHelper
     * @param RequestToOrderHelper $requestToOrderHelper
     * @param Cart $cartHelper
     * @param RedirectInterface $redirect
     */
    public function __construct(
        Session              $checkoutSession,
        ManagerInterface     $messageManager,
        CustomerSession      $customerSession,
        Config               $companyTypeConfig,
        PurchasePermission   $purchasePermissionValidator,
        ReportingBrand       $reportingBrandHelper,
        RequestToOrderHelper $requestToOrderHelper,
        Cart                 $cartHelper,
        RedirectInterface    $redirect
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->_messageManager = $messageManager;
        $this->customerSession = $customerSession;
        $this->companyTypeConfig = $companyTypeConfig;
        $this->purchasePermissionValidator = $purchasePermissionValidator;
        $this->reportingBrandHelper = $reportingBrandHelper;
        $this->cartHelper = $cartHelper;
        $this->requestToOrderHelper = $requestToOrderHelper;
        $this->redirect = $redirect;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $quote = $this->checkoutSession->getQuote();
        $currentCustomer = $this->customerSession->getCustomer();
        $customerType = $this->companyTypeConfig->getCustomerCompanyType($currentCustomer);
        $errorMessage = $this->reportingBrandHelper->getReportingBrandErrorMessage();
        $hasInvalidItem = false;

        if ($customerType == Config::WHOLESALE) {
            $cartItems = $quote->getAllItems();

            foreach ($cartItems as $item) {
                $isItemValid = true;
                $product = $item->getProduct();
                if ($product->getTypeId() == Configurable::TYPE_CODE) {
                    $childItems = $item->getChildren();
                    foreach ($childItems as $childItem) {
                        $isItemValid =
                            $this->purchasePermissionValidator->validateProductById(
                                $childItem->getProductId(),
                                $currentCustomer
                            );
                        if (!$isItemValid) {
                            break;
                        }
                    }
                } else {
                    $isItemValid =
                        $this->purchasePermissionValidator->validateProductById(
                            $product->getId(),
                            $currentCustomer
                        );
                }

                $removeParams = $this->cartHelper->getDeletePostJson($item);
                $moveToRequestOrderParams = $this->requestToOrderHelper->getCartMovePostJson($item);
                $message = __(ReportingBrand::ITEM_ERROR_TEXT, $removeParams, $moveToRequestOrderParams);

                if (!$isItemValid) {
                    $hasInvalidItem = true;
                    $item->addErrorInfo(
                        ReportingBrand::ERROR_CODE,
                        ReportingBrand::ERROR_ORIGIN_ID,
                        $message
                    );
                }
            }

            if ($hasInvalidItem) {
                $quote->addErrorInfo(
                    ReportingBrand::ERROR_CODE,
                    ReportingBrand::ERROR_ORIGIN_ID,
                    $errorMessage
                );
                $this->_messageManager->addErrorMessage($errorMessage);

                $controller = $observer->getControllerAction();
                if ($controller) {
                    $this->redirect->redirect($controller->getResponse(), 'checkout/cart');
                }
            }
        }
    }
}
