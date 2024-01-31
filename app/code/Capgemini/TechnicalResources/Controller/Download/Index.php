<?php
/**
 * Capgemini_TechnicalResources
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\TechnicalResources\Controller\Download;

use Capgemini\TechnicalResources\Model\AttributeValueParser;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\DirectoryList as SystemDirectory;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;

class Index implements HttpGetActionInterface
{
    /**
     * @var Http
     */
    protected Http $request;

    /**
     * @var Filesystem
     */
    protected Filesystem $file;

    /**
     * @var File
     */
    protected File $fileDriver;

    /**
     * @var FileFactory
     */
    protected FileFactory $fileFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var AttributeValueParser
     */
    protected AttributeValueParser $attributeValueParser;

    /**
     * @var MessageManagerInterface
     */
    protected MessageManagerInterface $messageManager;

    /**
     * @var RedirectInterface
     */
    protected RedirectInterface $redirect;

    /**
     * @var RedirectFactory
     */
    protected RedirectFactory $resultRedirectFactory;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlInterface;

    /**
     * @var SystemDirectory
     */
    protected SystemDirectory $directoryList;

    /**
     * @var IoFile
     */
    protected IoFile $filesystemIo;

    /**
     * @var null|\ZipArchive
     */
    protected ?\ZipArchive $zipArchive;

    /**
     * @param Http $request
     * @param ProductRepositoryInterface $productRepository
     * @param AttributeValueParser $attributeValueParser
     * @param FileFactory $fileFactory
     * @param MessageManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param RedirectFactory $resultRedirectFactory
     * @param UrlInterface $urlInterface
     * @param SystemDirectory $directoryList
     * @param Filesystem $file
     * @param File $fileDriver
     * @param IoFile $filesystemIo
     */
    public function __construct(
        Http                       $request,
        ProductRepositoryInterface $productRepository,
        AttributeValueParser       $attributeValueParser,
        FileFactory                $fileFactory,
        MessageManagerInterface    $messageManager,
        RedirectInterface          $redirect,
        RedirectFactory            $resultRedirectFactory,
        UrlInterface               $urlInterface,
        SystemDirectory            $directoryList,
        Filesystem $file,
        File $fileDriver,
        IoFile $filesystemIo
    ) {
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->attributeValueParser = $attributeValueParser;
        $this->fileFactory = $fileFactory;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->urlInterface = $urlInterface;
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->fileDriver = $fileDriver;
        $this->filesystemIo = $filesystemIo;
        if (class_exists('\ZipArchive')) {
            $this->zipArchive = new \ZipArchive();
        }
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        try {
            if (!$this->zipArchive) {
                throw new LocalizedException(
                    new Phrase('zip file extension is not supported')
                );
            }
            $productId = $this->request->getParam('product_id');

            if ($productId) {
                $product = $this->productRepository->getById($productId);
                $dirPath = $this->directoryList->getRoot();
                $varDir = DirectoryList::VAR_DIR;
                $tempDir = 'tmp';
                $fileName = str_replace(['\\','/',':','*','?','"','<','>','|',' '],'_',$product->getSku()).'_technical_resources';
                $fileExtension = '.zip';
                $filePath = $dirPath . '/' . $varDir . '/' . $tempDir . '/' . $fileName  . $fileExtension;
                if (!$this->fileDriver->isExists($filePath)) {
                    $directoryWriter = $this->file->getDirectoryWrite($varDir);
                    $directoryWriter->create($tempDir);
                }

                $this->zipArchive->open($filePath, \ZipArchive::CREATE);

                $technicalResourcesArr = $this->attributeValueParser->process($product);

                foreach ($technicalResourcesArr as $resource) {
                    $resourceFilePath = $this->attributeValueParser->getFilePath($resource['filepath']);
                    $extension = pathinfo($resourceFilePath, PATHINFO_EXTENSION);
                    $copyFileFullPath = $dirPath . '/' . $varDir . '/' . $tempDir . '/' .
                        $resource['label'] . '.'.$extension;

                    if ($this->filesystemIo->cp($resourceFilePath, $copyFileFullPath)) {
                        $this->zipArchive->addFromString(
                            basename($copyFileFullPath),
                            file_get_contents($copyFileFullPath)
                        );

                        $this->filesystemIo->rm($copyFileFullPath);
                    }
                }

                $this->zipArchive->close();

                return $this->fileFactory->create(
                    $fileName . $fileExtension,
                    [
                        'type' => 'filename',
                        'value' => $filePath,
                        'rm' => true
                    ],
                    DirectoryList::VAR_DIR,
                    'application/zip'
                );
            }
        } catch (Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('Unable to create the technical resources archive file for downloading.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->urlInterface->getUrl($this->redirect->getRefererUrl()));

        return $resultRedirect;
    }
}
