<?php
/**
 * Capgemini_PartnersInsight
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\PartnersInsight\Controller\Api;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Math\Random;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Mock API controller
 */
class Mock implements HttpGetActionInterface
{
    /**
     * @var ResultFactory
     */
    protected $resultFactory;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var Random
     */
    protected $random;

    /**
     * Constructor
     *
     * @param ResultFactory $resultFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $url
     * @param Random $random
     */
    public function __construct(
        ResultFactory $resultFactory,
        ScopeConfigInterface $scopeConfig,
        UrlInterface $url,
        Random $random
    ){
        $this->resultFactory = $resultFactory;
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
        $this->random = $random;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $token = $this->random->getRandomString(6);
        $path = $this->scopeConfig->getValue('partners_insight/general/api_mock_resource_path', ScopeInterface::SCOPE_STORE);
        $resourceUrl = $this->url->getUrl($path, ['token' => $token]);

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData([
            "success" => true,
            "message" => "Your request was successfully completed",
            "errors" => [],
            "data" => [
                "integrationToken" => $token,
                "resourceUrl" => $resourceUrl
            ]
        ]);
        return $resultJson;
    }
}