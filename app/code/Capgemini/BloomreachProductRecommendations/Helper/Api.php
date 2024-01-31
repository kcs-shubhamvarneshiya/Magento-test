<?php

namespace Capgemini\BloomreachProductRecommendations\Helper;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation;
use Capgemini\BloomreachProductRecommendations\RequestStringBuilding\RequestStringBuilderFactory;
use Capgemini\BloomreachThematic\Helper\Data;
use Exception;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;

class Api extends AbstractHelper
{
    const HEADERS = [
        'Content-Type'    => 'application/json',
        'Connection'      => 'keep-alive',
        'Accept'          => '*/*',
        'Accept-Encoding' => 'gzip, deflate, br'
    ];

    /**
     * @var Http
     */
    protected $_request;

    private ClientFactory $clientFactory;
    private SerializerInterface $serializer;
    private Session $customerSession;
    private RequestStringBuilderFactory $requestStringBuilderFactory;
    private Data $dataHelper;

    /**
     * @param Context $context
     * @param ClientFactory $clientFactory
     * @param SerializerInterface $serializer
     * @param Session $customerSession
     * @param RequestStringBuilderFactory $requestStringBuilderFactory
     * @param Data $dataHelper
     */
    public function __construct(
        Context $context,
        ClientFactory $clientFactory,
        SerializerInterface $serializer,
        Session $customerSession,
        RequestStringBuilderFactory $requestStringBuilderFactory,
        Data $dataHelper
    ) {
        parent::__construct($context);
        $this->clientFactory = $clientFactory;
        $this->serializer = $serializer;
        $this->customerSession = $customerSession;
        $this->requestStringBuilderFactory = $requestStringBuilderFactory;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param $widgetData
     * @return array|false
     * @throws ValidatorException|GuzzleException
     */
    public function callApi($widgetData): bool|array
    {
        $recWidgetType = $widgetData[Recommendation::WIDGET_OPTION_REC_WIDGET_TYPE] ?? null;

        if (!$recWidgetType) {
            $this->_logger->error(
                Recommendation::WIDGET_OPTION_REC_WIDGET_TYPE . ' is not defined.',
                ['method' => __METHOD__]
            );

            return false;
        }

        $generalRequestData = $this->obtainGeneralRequestData();
        $data = $widgetData + $generalRequestData;

        if (count($data) !== count($generalRequestData) + count($widgetData)) {
            $this->_logger->error(
                'Ambiguity detected: general request data and widget data have common key(s)',
                ['method' => __METHOD__]
            );

            return false;
        }

        try {
            $requestStringBuilder = $this->requestStringBuilderFactory->create($recWidgetType, $data);
        } catch (Exception $exception) {
            $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

            return false;
        }

        $requestString = $requestStringBuilder->build();

        return $this->performRequest($requestString);
    }

    /**
     * @param string $path
     * @return mixed
     */
    private function getStoreConfigValue(string $path): mixed
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    private function obtainGeneralRequestData(): array
    {
        return  [
            'account_id' => $this->getStoreConfigValue(ConfigurationSettingsInterface::SETTINGS_ACC_ID),
            'domain_key' => $this->getStoreConfigValue(ConfigurationSettingsInterface::SETTINGS_DOMAIN_KEY),
            'request_id' => $this->getStoreConfigValue('bloomreach_settings/seo_widgets/thematic_request_id'),
            '_br_uid_2'  => $this->dataHelper->getTrackingCookie(),
            'fields'     => $this->getStoreConfigValue('bloomreach_settings/seo_widgets/thematic_product_attributes'),
            'url'        => $this->_request->getUriString(),
            'ref_url'    => $this->_httpHeader->getHttpReferer() ?: $this->_request->getUriString(),
            'user_id'    => $this->customerSession->getCustomerId(),
            'start'      => 0,
        ];
    }

    /**
     * @param $requestString
     * @return array|false
     * @throws GuzzleException
     */
    private function performRequest($requestString): bool|array
    {
        $baseUri = $this->getStoreConfigValue(ConfigurationSettingsInterface::SETTINGS_ENDPOINT_WIDGETS) === 'stage'
            ? ConfigurationSettingsInterface::STAGING_API_ENDPOINT_WIDGET
            : ConfigurationSettingsInterface::PRODUCTION_API_ENDPOINT_WIDGET;
        $client = $this->clientFactory->create([
            'config' => [
                'base_uri'        => $baseUri,
                'allow_redirects' => false,
                'timeout'         => 120
            ]
        ]);
        $options = [
            'headers' => self::HEADERS
        ];

        try {
            $response = $client->request('GET', $requestString , $options);

            if (in_array($response->getStatusCode(), [200, 201])) {

                return $this->serializer->unserialize($response->getBody());
            }
        } catch (Exception $exception) {
            $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);
        }

        return false;
    }
}
