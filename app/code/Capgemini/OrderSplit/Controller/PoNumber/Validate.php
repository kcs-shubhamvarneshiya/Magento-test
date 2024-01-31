<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Controller\PoNumber;

use Capgemini\OrderSplit\Api\Checkout\Validator\ResultInterface;
use Capgemini\OrderSplit\Model\Checkout\PoNumberValidationProcessor;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

class Validate implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * @var PoNumberValidationProcessor
     */
    protected $validatorProcessor;

    /**
     * @param RequestInterface $request
     * @param JsonFactory $jsonResultFactory
     * @param PoNumberValidationProcessor $validatorProcessor
     */
    public function __construct(
        RequestInterface $request,
        JsonFactory $jsonResultFactory,
        PoNumberValidationProcessor $validatorProcessor
    ) {
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->validatorProcessor = $validatorProcessor;
    }

    /**
     * @return Json
     */
    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        $result->setData(['result' => true]);
        try {
            $data = $this->request->getPostValue();
            $poNumberValue = $data['po_number'] ?? null;
            $businessBackendValue = $data['division'] ?? null;

            /**
             * @var ResultInterface $validationResult
             */
            $validationResult = $this->validatorProcessor->validate([$poNumberValue, $businessBackendValue]);
            $result->setData(
                [
                    'result' => $validationResult->getIsValid(),
                    'errors' => $validationResult->getErrorMessages()
                ]
            );
        } catch (LocalizedException $exception) {
            $result->setData(['message' => $exception->getMessage()]);
            $result->setHttpResponseCode(500);
        }
        return $result;
    }
}
