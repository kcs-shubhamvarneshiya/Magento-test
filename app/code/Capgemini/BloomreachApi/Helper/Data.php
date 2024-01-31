<?php

namespace Capgemini\BloomreachApi\Helper;

use Bloomreach\Connector\Block\ConfigurationSettingsInterface;
use Capgemini\BloomreachApi\Plugin\ConfigSave;
use Capgemini\CompanyType\Model\Config;
use Exception;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
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
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
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
    private $data = [];
    /**
     * @var Config
     */
    private $companyTypeConfig;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ClientFactory $clientFactory
     * @param SerializerInterface $serializer
     * @param Config $companyTypeConfig
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ClientFactory $clientFactory,
        SerializerInterface $serializer,
        Config $companyTypeConfig
    ) {
        parent::__construct($context);

        $this->productRepository = $productRepository;
        $this->clientFactory = $clientFactory;
        $this->serializer = $serializer;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->companyTypeConfig = $companyTypeConfig;
    }

    /**
     * @param $productListTemplate
     * @param $priceContainerOpeningTag
     * @param $classAttribute
     * @return string
     */
    public function prepareLoggedInTemplate($productListTemplate, $priceContainerOpeningTag, $classAttribute): string
    {
        $priceContainerOpeningTagStart = strpos($productListTemplate, $priceContainerOpeningTag);

        if ($priceContainerOpeningTagStart === false) {

            return '';
        }

        $priceContainerOpeningTagEnd = $priceContainerOpeningTagStart + strlen($priceContainerOpeningTag);

        $openingTagClipped = strstr($priceContainerOpeningTag, ' ', true);
        $closingTagClipped = str_replace('<', '</', $openingTagClipped);

        // A function for retrieving a closing tag position for a provided opening tag
        $getClosingTagStartPosition = function ($position) use (
            $productListTemplate,
            $openingTagClipped,
            $closingTagClipped,
            &$getClosingTagStartPosition
        ) {
            static $counter = 1;

            $firstOpeningTagPos = strpos($productListTemplate, $openingTagClipped, $position);
            $firstClosingTagPos = strpos($productListTemplate, $closingTagClipped, $position);

            if ($firstClosingTagPos === false) {

                return false;
            }

            if ($firstOpeningTagPos === false) {
                $firstOpeningTagPos =  PHP_INT_MAX;
            }

            $counter += $firstClosingTagPos <=> $firstOpeningTagPos;

            if ($counter > 0) {

                return $getClosingTagStartPosition(min($firstOpeningTagPos, $firstClosingTagPos) + 1);
            }

            return $firstClosingTagPos;
        };

        $priceContainerClosingTagStart = $getClosingTagStartPosition($priceContainerOpeningTagStart + 1);

        if ($priceContainerClosingTagStart === false) {

            return '';
        }

        $topToOpeningTagEndPart = substr($productListTemplate, 0, $priceContainerOpeningTagEnd);
        $closingTagStartToBottomPart = substr($productListTemplate, $priceContainerClosingTagStart);
        $specialPriceLabel = ConfigSave::SPECIAL_PRICE_LABEL_PLACEHOLDER;
        $classAttribute = preg_replace('#\s*<%.*%>#', '--strike-through-not', $classAttribute);
        $priceContainerContent = <<<content
<%
  const salePrice = variant.sku_sale_price !== undefined ? variant.sku_sale_price : product.sale_price;
  const price = variant.sku_price !== undefined ? variant.sku_price : product.price;
%>
<% if (salePrice !== undefined) { %>
<span class="price-box price-final_price">
    <span class="old-price">
        <span class="price-container price-final_price tax weee">
            <span class="price-label">Retail</span>
            <span <% if (salePrice !== undefined) { %>$classAttribute<% } %>>
                <span class="price-wrapper">
                    <span class="price"><%= config.format_money(price.toFixed(2) * 100) %></span>
                </span>
            </span>
        </span>
    </span>

    <span class="special-price">
        <span class="price-container price-final_price tax weee">
            <span class="price-label">$specialPriceLabel</span>
            <span class="price-wrapper">
                <span class="price"><%= config.format_money((salePrice !== undefined ? salePrice : price).toFixed(2) * 100) %></span>
            </span>
        </span>
    </span>
</span>
<% } %>
<% if (salePrice === undefined) { %>
<span class="price-box price-final_price">
    <span class="price-container price-final_price tax weee">
        <span class="price-wrapper">
            <span class="price"><%= config.format_money(price.toFixed(2) * 100) %></span>
        </span>
    </span>
</span>
<% } %>
content;

        return $topToOpeningTagEndPart . $priceContainerContent . $closingTagStartToBottomPart;
    }

    /**
     * @param $type
     * @return array|false
     * @throws GuzzleException
     */
    public function process($type)
    {
        $client = $this->clientFactory->create([
            'config' => [
                'base_uri'        => $this->getApiEndPointUrl($type),
                'allow_redirects' => false,
                'timeout'         => 120
            ]
        ]);
        $options = [
            'headers' => self::HEADERS
        ];
        $requestString = $this->_request->getRequestString();
        $startPos = strlen($this->_request->getFullActionName()) + 2;
        $paramsString = substr($requestString, $startPos);

        try {
            $response = $client->request('GET', $paramsString , $options);

            if (in_array($response->getStatusCode(), [200, 201])) {
                $result = $this->serializer->unserialize($response->getBody());

                return $this->makeAdjustments($result);
            }

            return false;
        } catch (Exception $exception) {
            $this->_logger->error('Capgemini_BloomreachApi: ' . $exception->getMessage());
        }

        return false;
    }

    /**
     * @param string $type
     * @return string
     */
    private function getApiEndPointUrl(string $type): string
    {
        $settingsEndpointConstName = 'SETTINGS_ENDPOINT_' . strtoupper($type);
        $val = $this->scopeConfig->getValue(
            constant(ConfigurationSettingsInterface::class . '::' . $settingsEndpointConstName),
            ScopeInterface::SCOPE_STORE
        );

        return match ($type) {
            'search' => $val === 'stage'
                ? ConfigurationSettingsInterface::STAGING_API_ENDPOINT_SEARCH
                : ConfigurationSettingsInterface::PRODUCTION_API_ENDPOINT_SEARCH,
            'category' => $val === 'stage'
                ? ConfigurationSettingsInterface::STAGING_API_ENDPOINT_COLLECTION
                : ConfigurationSettingsInterface::PRODUCTION_API_ENDPOINT_COLLECTION,
            default => '',
        };
    }

    /**
     * @param $data
     * @return array
     */
    private function makeAdjustments($data): array
    {
        $docs = $data['response']['docs'];
        $skus = array_column($docs, 'pid');
        $skuIndexedDocs = array_combine($skus, $docs);
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('sku',$skus, 'in')
            ->create();
        $products = $this->productRepository->getList($searchCriteria)->getItems();
        /** @var Product $product */
        foreach ($products as $product) {
            if (!isset($skuIndexedDocs[$product->getSku()]['sale_price'])) {

                continue;
            }

            if ($this->companyTypeConfig->getCustomerCompanyType() === Config::WHOLESALE) {
                unset($skuIndexedDocs[$product->getSku()]['sale_price']);
                $skuIndexedDocs[$product->getSku()]['variants'][0]['sku_price'] = $product->getFinalPrice();

                continue;
            }

            if ($skuIndexedDocs[$product->getSku()]['sale_price'] != $product->getFinalPrice()) {
                $skuIndexedDocs[$product->getSku()]['sale_price'] = $product->getFinalPrice();
            } else if ($skuIndexedDocs[$product->getSku()]['sale_price'] == $skuIndexedDocs[$product->getSku()]['price']) {
                unset($skuIndexedDocs[$product->getSku()]['sale_price']);
            }
        }
        $data['response']['docs'] = array_values($skuIndexedDocs);

        return $data;
    }
}
