<?php
/**
 * Capgemini_WishlistPdf
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\WishlistPdf\Controller\Pdf;

use Capgemini\CompanyType\Model\Config;
use Dompdf\Dompdf;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Wishlist\Controller\WishlistProviderInterface;

/**
 * Print Wishlist PDF action
 */
class Index implements HttpGetActionInterface
{
    /**
     * @var Dompdf
     */
    protected $domPdf;
    /**
     * @var ResultFactory
     */
    protected $resultFactory;
    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;
    /**
     * @var RedirectInterface
     */
    protected $redirect;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;
    /**
     * @var Config
     */
    protected $customerTypeConfig;
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param Dompdf $domPdf
     * @param ResultFactory $resultFactory
     * @param MessageManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param Session $customerSession
     * @param WishlistProviderInterface $wishlistProvider
     * @param Config $customerTypeConfig
     * @param RequestInterface $request
     */
    public function __construct(
        Dompdf $domPdf,
        ResultFactory $resultFactory,
        MessageManagerInterface $messageManager,
        RedirectInterface $redirect,
        Session $customerSession,
        WishlistProviderInterface $wishlistProvider,
        Config $customerTypeConfig,
        RequestInterface $request
    ) {
        $this->domPdf = $domPdf;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->customerSession = $customerSession;
        $this->wishlistProvider = $wishlistProvider;
        $this->customerTypeConfig = $customerTypeConfig;
        $this->request = $request;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        try {
            $this->validatePricingPermissions();
            $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
            $pdfData = $this->getPdfData($result);
            $result->setContents($pdfData['raw']);
            $result->setHeader('Content-Type', 'application/pdf', true);
            $result->setHeader('Content-Disposition', 'attachment; filename="' . $pdfData['filename'] . '.pdf"', true);
            $result->setHeader('Cache-Control', 'private, max-age=0', true);
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setPath($this->redirect->getRefererUrl());
        }
        return $result;
    }

    /**
     * Validate permissions to use selected pricing option
     *
     * @throws LocalizedException
     */
    protected function validatePricingPermissions()
    {
        $pricingType = $this->request->getParam('pricing_type');
        if (!$pricingType) {
            throw new LocalizedException(__('Missing price_type parameter'));
        }
        if (in_array($pricingType, [3,4])) {
            $customer = $this->customerSession->getCustomer();
            $customerType = $this->customerTypeConfig->getCustomerCompanyType($customer);
            if (!in_array($customerType, [Config::WHOLESALE, Config::TRADE])) {
                throw new LocalizedException(__('Permissions denied'));
            }
            if ($pricingType == 4 && !$this->request->getParam('percent')) {
                throw new LocalizedException(__('Missing percent parameter'));
            }
        }
    }

    /**
     * Prepare PDF file content
     *
     * @return array
     */
    protected function getPdfData(ResultInterface $page)
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $wishlist = $this->wishlistProvider->getWishlist();
        if (!$wishlist) {
            throw new LocalizedException(__('This wishlist isn\'t exist.'));
        }
        $block = $page->getLayout()->getBlock('whishlist.pdf');
        $block->setWishlist($wishlist);

        $html = '<html><head>' . $block->getStylesLink() . '</head> <body>' . '<body>' . $block->toHtml() . '</body></html>';

        $this->domPdf->getOptions()->setIsRemoteEnabled(true);
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $pdf = $this->domPdf->output();
        $data = [
            'raw' => $pdf,
            'filename' => $this->getFilename($wishlist)
        ];

        return $data;
    }

    /**
     * Get name for PDF file.
     *
     * @param \Magento\Wishlist\Model\Wishlist $wishlist
     * @return array|string|string[]
     */
    private function getFilename(\Magento\Wishlist\Model\Wishlist $wishlist)
    {
        return str_replace(['\\','/',':','*','?','"','<','>','|',' '],'-', strtolower($wishlist->getName()));
    }
}
