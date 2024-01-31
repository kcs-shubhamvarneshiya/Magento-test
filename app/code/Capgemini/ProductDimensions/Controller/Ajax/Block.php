<?php
/**
 * Capgemini_ProductDimensions
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\ProductDimensions\Controller\Ajax;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_ProductDimensions
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
abstract class Block implements HttpGetActionInterface
{
    /**
     * Short description
     *
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * Short description
     *
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * Short description
     *
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * Short description
     *
     * @var RawFactory
     */
    protected RawFactory $rawFactory;

    /**
     * Short description
     *
     * @param RequestInterface $request Param description
     * @param PageFactory $resultPageFactory Param description
     * @param ProductRepository $productRepository Param description
     * @param RawFactory $rawFactory Param description
     */
    public function __construct(
        RequestInterface $request,
        PageFactory $resultPageFactory,
        ProductRepository $productRepository,
        RawFactory $rawFactory
    ) {
        $this->request = $request;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->rawFactory = $rawFactory;
    }
}
