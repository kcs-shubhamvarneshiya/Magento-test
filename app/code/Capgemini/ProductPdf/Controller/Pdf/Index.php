<?php
/**
 * Capgemini_ProductPdf
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\ProductPdf\Controller\Pdf;

use Capgemini\ProductPdf\Block\Product;
use Capgemini\ProductPdf\Helper\Data;
use Dompdf\Dompdf;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

class Index implements HttpGetActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Dompdf
     */
    private $pdf;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @param RequestInterface $request
     * @param ProductRepository $productRepository
     * @param Filesystem $filesystem
     * @param PageFactory $resultPageFactory
     * @param ResultFactory $resultFactory
     * @param MessageManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(
        RequestInterface $request,
        ProductRepository $productRepository,
        Filesystem $filesystem,
        PageFactory $resultPageFactory,
        ResultFactory $resultFactory,
        MessageManagerInterface $messageManager,
        RedirectInterface $redirect,
        LoggerInterface $logger,
        Data $helper
    ){
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->filesystem = $filesystem;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @return ResponseInterface|Raw|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
            $pdfData = $this->getPdfData();
            $result->setContents($pdfData['raw']);
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setPath($this->redirect->getRefererUrl());

            return $result;
        }
        $result->setHeader('Content-Type', 'application/pdf', true);
        $result->setHeader('Content-Disposition', 'attachment; filename="' . $pdfData['filename'] . '.pdf"', true);
        $result->setHeader('Cache-Control', 'private, max-age=0', true);

        return $result;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    protected function getPdfData()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $productId = (int) $this->request->getParam(Data::HTTP_PARAM_ID);
        $optionsValues = $this->request->getParam(Data::HTTP_PARAM_OPTIONS);
        $optionPricing = (int) $this->request->getParam(Data::HTTP_PARAM_PRICING);

        /** @var Product $block */
        $block = $resultPage->getLayout()->getBlock("product.view.pdf");
        $block->setProductId($productId);
        if ($optionsValues) {
            $block->setOptions(explode(',',$optionsValues));
        }
        if ($optionPricing == Product::VALUE_PRICING_DISABLE) {
            $block->setPricingEnable(false);
        } else {
            $block->setPriceType($optionPricing);
        }

        $productHtml =  '<html><head>' . $block->getStylesLink() . '</head> <body>' . $block->toHtml() . '</body></html>';

        $block->setTemplate("Capgemini_ProductPdf::product/gallery.phtml");
        $galleryHtml = '<html><head>' . $block->getStylesLink() . '</head> <body>' . $block->toHtml() . '</body></html>';

        $productPdf = $this->helper->getPdfWriter();
        $productPdf->loadHtml($productHtml);
        $productPdf->set_option('isRemoteEnabled', true);
        $productPdf->render();
        $productPageRaw = $productPdf->output();

        $imageGalleryPdf = $this->helper->getPdfWriter();
        $imageGalleryPdf->loadHtml($galleryHtml);
        $imageGalleryPdf->set_option('isRemoteEnabled', true);
        $imageGalleryPdf->render();
        $galleryPageRaw = $imageGalleryPdf->output();

        return [
            'raw' => $this->helper->mergePdf($productPageRaw, $galleryPageRaw),
            'filename' => $this->getFilename($block->getProduct())
        ];
    }

    /**
     * @param ProductInterface $product
     * @return array|string|string[]
     */
    private function getFilename(ProductInterface $product)
    {
        return str_replace(' ', '-', strtolower($product->getName()).'-'.$product->getSku());
    }
}
