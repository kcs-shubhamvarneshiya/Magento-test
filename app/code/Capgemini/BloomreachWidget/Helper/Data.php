<?php

namespace Capgemini\BloomreachWidget\Helper;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;
use GuzzleHttp\ClientFactory;

class Data extends AbstractHelper implements ConfigurationSettingsInterface
{
    const XML_CONFIG_PATH_IS_SEO_WIDGET_ENABLED = 'bloomreach_settings/seo_widgets/enabled';
    const XML_CONFIG_PATH_IS_SEO_ITEM_DESCRIPTION_ENABLED = 'bloomreach_settings/seo_widgets/enabled_item_description';
    const XML_CONFIG_PATH_SEO_WIDGET_BASE_URL_PARAMETER = 'bloomreach_settings/seo_widgets/base_url_parameter';
    const STAGING_WIDGET_API_ENDPOINT_URL = 'https://bsapi-test.brsrvr.com/v3/fetch_widget';
    const PRODUCTION_WIDGET_API_ENDPOINT_URL = 'https://bsapi-e.brsrvr.com/v3/fetch_widget';
    const HEADERS = [
        'Content-Type'    => 'application/json',
        'Connection'      => 'keep-alive',
        'Accept'          => '*/*',
        'Accept-Encoding' => 'gzip, deflate, br'
    ];
    /**
     * @var ClientFactory
     */
    private $clientFactory;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var array
     */
    private $cachedResponses = [];

    /**
     * @param Context $context
     * @param ClientFactory $clientFactory
     * @param SerializerInterface $serializer
     */
    public function __construct(Context $context, ClientFactory $clientFactory, SerializerInterface $serializer)
    {
        parent::__construct($context);

        $this->clientFactory = $clientFactory;
        $this->serializer = $serializer;
    }

    /**
     * @var Http
     */
    protected $_request;

    /**
     * @return mixed
     */
    public function isEnabled(): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_IS_SEO_WIDGET_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isItemDescriptionEnabled(): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_IS_SEO_ITEM_DESCRIPTION_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getBaseUrlParameter(): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_CONFIG_PATH_SEO_WIDGET_BASE_URL_PARAMETER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getFullActionName(): string
    {
        return $this->_request->getFullActionName();
    }

    /**
     * @param string $pageType
     * @param string|null $baseUrlParameter
     * @param bool $useCache
     * @return array
     */
    public function callApi(
        string $pageType,
        ?string $baseUrlParameter = null,
        bool   $useCache = true
    ): array {
        $client = $this->clientFactory->create([
            'config' => [
                'base_uri'        => $this->getBrWidgetEndPointUrl(),
                'allow_redirects' => false,
                'timeout'         => 120
            ]
        ]);

        $options = [
            'headers' => self::HEADERS
        ];

        $paramsString = $this->buildParamsString($pageType, $baseUrlParameter);

        if ($useCache === true && isset($this->cachedResponses[$paramsString])) {

            return $this->cachedResponses[$paramsString];
        }

        try {
            $response = $client->request('GET', $paramsString , $options);

            if (!in_array($response->getStatusCode(), [200, 201])) {

                return [];
            }
        } catch (GuzzleException $exception) {
            $this->_logger->error('Capgemini_BloomreachWidget: ' . $exception->getMessage());

            return [];
        }

        $this->cachedResponses[$paramsString] = $this->serializer->unserialize($response->getBody());

        return $this->cachedResponses[$paramsString];
    }

    /**
     * Get Widget End Point Url
     *
     * @return string
     */
    public function getBrWidgetEndPointUrl()
    {
        $val = $this->scopeConfig->getValue(self::SETTINGS_ENDPOINT_WIDGETS);
        if ($val=='stage') {
            return self::STAGING_WIDGET_API_ENDPOINT_URL;
        }
        return self::PRODUCTION_WIDGET_API_ENDPOINT_URL;
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public function getResponseDatum($key)
    {
        foreach ($this->cachedResponses as $cachedResponse) {
            $datum = $cachedResponse[$key] ?? '';

            if ($datum) {

                return $datum;
            }
        }

        return '';
    }

    /**
     * @param string $pageType
     * @param string|null $baseUrlParameter
     * @return string
     */
    private function buildParamsString(string $pageType, ?string $baseUrlParameter): string
    {
        $urlParameter = $this->_request->getUriString();

        if (filter_var($baseUrlParameter, FILTER_VALIDATE_URL)) {
            $urlParameter = str_replace($this->_getUrl(null), $baseUrlParameter, $urlParameter);
        } else {
            $this->_logger->warning(
                'Capgemini_BloomreachWidget: provided base URL parameter is invalid. Current base URL is used instead.',
                ['provided' => $baseUrlParameter]
            );
        }

        $parameters = [
            'acct_id'       => $this->scopeConfig->getValue(ConfigurationSettingsInterface::SETTINGS_ACC_ID, ScopeInterface::SCOPE_STORE),
            'acct_auth'     => $this->scopeConfig->getValue(ConfigurationSettingsInterface::SETTINGS_AUTH_KEY, ScopeInterface::SCOPE_STORE),
            'ptype'         => $pageType,
            'url'           => urlencode($urlParameter),
            'output_format' => 'json'
        ];

        $paramsString = '?';

        foreach ($parameters as $key => $value) {
            $paramsString .= $key . '=' . $value . '&';
        }

        return rtrim($paramsString, '&');
    }
}
