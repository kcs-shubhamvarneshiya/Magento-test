<?php
/**
 * Capgemini_VcServicePoValidationMock
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

namespace Capgemini\VcServicePoValidationMock\Controller\Rest;

use Magento\Framework\Webapi\Rest\Request as RestRequest;
use Magento\Framework\Webapi\Rest\Response as RestResponse;
use Magento\Webapi\Controller\Rest\RequestProcessorInterface;

/**
 * Mock processor for PO validation API request
 */
class PoValidationMockProcessor implements RequestProcessorInterface
{
    protected const PROCESSOR_PATH = "/^\\/V1\\/vcmock\\/povalidation(\\/|$)/";
    /**
     * @var RestResponse
     */
    protected $response;

    public function __construct(
        RestResponse $response
    ) {
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function canProcess(RestRequest $request)
    {
        if (
            preg_match(self::PROCESSOR_PATH, $request->getPathInfo()) === 1
            && $request->getMethod() === \Laminas\Http\Request::METHOD_POST
        ) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function process(RestRequest $request)
    {
        if (!$this->validateParams($request)) {
            return $this->response;
        }
        $data = $request->getBodyParams();
        $customer = $data[0]['customer'];
        $poNumber = $data[0]['poRef'];
        $responseData = [
            [
                'customer' => $customer,
                'poRef' => $poNumber,
                'isValidPo' => $this->validatePoNumber($poNumber)
            ]
        ];
        return $this->response->setStatusCode(RestResponse::STATUS_CODE_200)
            ->prepareResponse($responseData);
    }

    /**
     * Validate input params and prepare error response if not valid
     *
     * @param RestRequest $request
     * @return bool
     */
    protected function validateParams(RestRequest $request)
    {
        $data = $request->getBodyParams();
        if (
            !is_array($data)
            || !isset($data[0])
            || !isset($data[0]['apiKey'])
            || !isset($data[0]['customer'])
            || !isset($data[0]['poRef'])
        ) {
            $this->response->setStatusCode(RestResponse::STATUS_CODE_404)
                ->prepareResponse(['message' => 'Invalid request data']);
            return false;
        }
        if (empty($data[0]['apiKey'])) {
            $this->response->setStatusCode(RestResponse::STATUS_CODE_401)
                ->prepareResponse(['message' => 'Invalid apiKey']);
            return false;
        }
        return true;
    }

    /**
     * Validate PO number
     *
     * @param string $poNumber
     * @retrun bool
     */
    protected function validatePoNumber($poNumber)
    {
        return is_string($poNumber) && strlen($poNumber) > 0 && is_numeric(substr($poNumber, -1));
    }
}
