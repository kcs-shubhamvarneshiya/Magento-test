<?php

namespace Capgemini\BloomreachProductRecommendations\Block\Widget;

use Bloomreach\Connector\Block\Widget\Recommendation as Orig;
use Capgemini\BloomreachApi\ViewModel\Head\ScriptInit;
use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation\Default\Collection;
use Capgemini\BloomreachProductRecommendations\Helper\Api;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Math\Random;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;

class Recommendation extends Orig
{
    const WIDGET_OPTION_TITLE = 'title';
    const WIDGET_OPTION_REC_WIDGET_ID = 'rec_widget_id';
    const WIDGET_OPTION_REC_WIDGET_TYPE = 'rec_widget_type';
    const WIDGET_OPTION_CATEGORY_ID = 'category_id';
    const WIDGET_OPTION_KEYWORD_QUERY = 'keyword_query';
    const WIDGET_OPTION_ITEM_IDS = 'item_ids';
    const WIDGET_OPTION_PRODUCTS_VISIBLE = 'products_visible';
    const WIDGET_OPTION_PRODUCTS_TO_FETCH = 'products_to_fetch';
    const DELEGATED_DATA_KEYS = [
        'WIDGET'       => 'widget_data',
        'API_RESPONSE' => 'api_response_data',
        'OTHER'        => 'other_data'
    ];
    const COLLECTION_BLOCK_NAME_PATTERN = 'br.pathways.recommendations_%d';

    private static int $counter = 0;
    private Api $apiHelper;
    private ScriptInit $scriptInit;
    private Http $request;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Json $jsonSerializer,
        Random $randomGenerator,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger,
        Data $catalogHelper,
        Http $request,
        Api $apiHelper,
        ScriptInit $scriptInit,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $jsonSerializer,
            $randomGenerator,
            $productRepository,
            $logger,
            $catalogHelper,
            $request,
            $data
        );
        $this->apiHelper = $apiHelper;
        $this->scriptInit = $scriptInit;
        $this->request =$request;
    }

    protected function _beforeToHtml()
    {
        $widgetData = $this->getPreparedData();

        try {
            if (!$apiResponseData = $this->apiHelper->callApi($widgetData)) {

                throw new \Exception('Falsy Bloomreach API response.');
            }

            $collectionBlockName = self::getNameForCollectionBlock();
            $collectionBlock = $this->getLayout()->createBlock(Collection::class, $collectionBlockName);
        } catch (GuzzleException|Exception $exception) {
            $this->_logger->error($exception->getMessage(), ['method' => __METHOD__]);

            return parent::_beforeToHtml();
        }

        $collectionBlock->setData(self::DELEGATED_DATA_KEYS['WIDGET'], $widgetData);
        $collectionBlock->setData(self::DELEGATED_DATA_KEYS['API_RESPONSE'], $apiResponseData);

        $otherData = [
            'is_core_pixel_enabled' => $this->scriptInit->isPixelEnabled(),
            'query_text' => $this->scriptInit->isSearchResultPage() ? $this->_request->getParam('q') : null,
            'product_id' => $this->scriptInit->hasProduct() ? $this->scriptInit->getPixelProdId() : null
        ];

        $collectionBlock->setData(self::DELEGATED_DATA_KEYS['OTHER'], $otherData);

        $this->setChild($collectionBlockName, $collectionBlockName);
        self::$counter++;

        return parent::_beforeToHtml();
    }

    public static function getNameForCollectionBlock(): string
    {
        return sprintf(self::COLLECTION_BLOCK_NAME_PATTERN, self::$counter);
    }

    private function getPreparedData()
    {
        $widgetData = $this->getData();

        if (isset($widgetData[self::WIDGET_OPTION_ITEM_IDS])
            && $this->request->getFullActionName() == 'catalog_product_view'
        ) {
            $widgetData[self::WIDGET_OPTION_ITEM_IDS] = $this->getCurrentProduct()->getSku();
        }

        return $widgetData;
    }
}
