<?php
/**
 * Capgemini_CompanyType
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CompanyType\Controller\Product;

use Capgemini\CompanyType\Model\Product\Validator;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Validate implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonResultFactory;

    /**
     * @var Validator
     */
    protected Validator $productValidator;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @param JsonFactory $jsonResultFactory
     * @param Validator $productValidator
     * @param RequestInterface $request
     */
    public function __construct(
        JsonFactory $jsonResultFactory,
        Validator $productValidator,
        RequestInterface $request
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productValidator = $productValidator;
        $this->request = $request;
    }

    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        $productId = $this->request->getParam('id');

        $result->setData(
            [
                'success' => $this->productValidator->validate($productId),
            ]
        );

        return $result;
    }
}
