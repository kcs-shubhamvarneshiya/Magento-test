<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\RequisitionList\Controller\Requisition;

use Dompdf\Options as Options;
use Lyonscg\RequisitionList\Helper\PdfMerge;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Pdf
 * @package Lyonscg\RequisitionList\Controller\Requisition
 */
class Pdf extends \Magento\Framework\App\Action\Action
{
    const FONT_DIR = 'font_dir';

    /**
     * @var Options
     */
    protected $options;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Dompdf\Dompdf
     */
    protected $dompdf;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PdfMerge
     */
    protected $pdfMerge;

    /**
     * @var string
     */
    private $projectName;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Pdf constructor.
     * @param Context $context
     * @param \Dompdf\Dompdf $dompdf
     * @param PageFactory $resultPageFactory
     * @param Filesystem $filesystem
     * @param Options $options
     * @param LoggerInterface $logger
     * @param PdfMerge $pdfMerge
     */
    public function __construct(
        Context         $context,
        \Dompdf\Dompdf  $dompdf,
        PageFactory     $resultPageFactory,
        Filesystem      $filesystem,
        Options         $options,
        LoggerInterface $logger,
        PdfMerge        $pdfMerge
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->options = $options;
        $this->dompdf = $dompdf;
        $this->logger = $logger;
        $this->pdfMerge = $pdfMerge;
        $this->filesystem = $filesystem;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
            $result->setContents($this->_renderPdf());
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setPath($this->_redirect->getRefererUrl());

            return $result;
        }
        $result->setHeader('Content-Type', 'application/pdf', true);
        $result->setHeader('Content-Disposition', 'attachment; filename="' . $this->projectName . '.pdf"', true);
        $result->setHeader('Cache-Control', 'private, max-age=0', true);
        foreach ($GLOBALS['_dompdf_warnings'] as $dompdfWarning) {
            $this->logger->warning('DOMPDF WARNING: ' . $dompdfWarning);
        }
        return $result;
    }

    /**
     * Set remote enable to render the images
     */
    public function setRemoteOption()
    {
        $this->options->setIsRemoteEnabled(true);
        $this->dompdf->setOptions($this->options);
    }

    /**
     * @return string|null
     * @throws LocalizedException
     */
    protected function _renderPdf()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        /** @var \Lyonscg\RequisitionList\Block\Quote $block */
        $block = $resultPage->getLayout()
            ->getBlock("requisition.quote.pdf");
        $projectName = $block->getRequisitionList()->getName();
        $this->projectName = preg_replace('#\s#', '_', $projectName);
        $html = $block->toHtml();

        $this->setRemoteOption();
        $this->dompdf->setOptions($this->options);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true
            ]
        ]);
        $this->dompdf->setHttpContext($contxt);

        $this->dompdf->loadHtml($html);
        $this->dompdf->set_option('isRemoteEnabled', true);
        try {
            $dirWrite = $this->filesystem
                ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);

            if (!$dirWrite->isDirectory(self::FONT_DIR)) {
                $dirWrite->create(self::FONT_DIR);
            }

            $path = $dirWrite->getAbsolutePath(self::FONT_DIR);
        } catch (FileSystemException $exception) {
            $this->logger->error($exception->getMessage());

            throw new LocalizedException(__('An error occurred while creating PDF. Please refer to the system administrator.'));
        }
        $this->dompdf->set_option('fontDir', $path);
        $this->dompdf->render();
        $canvas = $this->dompdf->getCanvas();
        $bottomPosition = $canvas->get_height() - 20;
        $canvas->page_text(10.0, $bottomPosition, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);

        $products = [];
        foreach ($block->getRequisitionListItems() as $item) {
            $block->setItem($item);
            $products[] = $block->getProductFromItem($item);
        }

        $rawPdf = $this->dompdf->output();
        if ($this->getRequest()->getParam('include_spec_sheets')) {
            $interlace = $this->getRequest()->getParam('pdf_type') === 'detail';
            return $this->pdfMerge->mergeForProducts($rawPdf, $products, $interlace);
        } else {
            return $rawPdf;
        }
    }
}
