<?php

namespace Capgemini\BloomreachProductRecommendations\RequestStringBuilding;

use Capgemini\BloomreachProductRecommendations\Block\Widget\Recommendation;
use Magento\Framework\Exception\ValidatorException;

abstract class RequestStringBuilder
{
    protected const SPECIFIC_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [];
    protected const SPECIFIC_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [];
    private const COMMON_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [
        'account_id'           => 'account_id',
        'domain_key'           => 'domain_key',
        'request_id'           => 'request_id',
        'userId'               => 'user_id',
        'numberOfItemsToShow'  => Recommendation::WIDGET_OPTION_PRODUCTS_VISIBLE,
        'numberOfItemsToFetch' => Recommendation::WIDGET_OPTION_PRODUCTS_TO_FETCH,
        'rows'                 => Recommendation::WIDGET_OPTION_PRODUCTS_TO_FETCH,
        'start'                => 'start'
    ];
    private const COMMON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING = [
        '_br_uid_2' => '_br_uid_2',
        'url'       => 'url',
        'ref_url'   => 'ref_url',
        'title'     => Recommendation::WIDGET_OPTION_TITLE,
        'fields'    => 'fields'
    ];
    private const COMMON_REQUIRED_PARAMS = [
        'account_id',
        'domain_key',
        '_br_uid_2',
        'url',
    ];
    private const BEFORE_QUERY_MARK_PARAM_TO_DATA_KEY_MAPPING = [
        'type' => Recommendation::WIDGET_OPTION_REC_WIDGET_TYPE,
        'id'   => Recommendation::WIDGET_OPTION_REC_WIDGET_ID,
    ];
    private const EMPTY_TYPES = [null, ''];

    protected array $requiredParams = [];

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->defineRequiredParams();
    }

    /**
     * @return string
     * @throws ValidatorException
     */
    public function build(): string
    {
        $requestString = '';

        $typeParamName = self::BEFORE_QUERY_MARK_PARAM_TO_DATA_KEY_MAPPING['type'];
        $typeParamValue = $this->data[$typeParamName];
        $requestString .= $typeParamValue . '/';
        $idParamName = self::BEFORE_QUERY_MARK_PARAM_TO_DATA_KEY_MAPPING['id'];
        $idParamValue = $this->data[$idParamName] ?? null;

        if (!in_array($idParamValue, self::EMPTY_TYPES)) {
            $requestString .= $idParamValue;
        }

        $requestString .= '?';

        $nonUrlEncoded = self::COMMON_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING
            + static::SPECIFIC_NON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING;

        foreach ($nonUrlEncoded as $paramName => $dataKey) {
            $paramValue = $this->data[$dataKey] ?? null;

            if (false === $this->checkEmpty($paramName, $paramValue)) {
                $requestString .= $paramName . '=' . $paramValue . '&';
            }
        }

        $urlEncoded = self::COMMON_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING
            + static::SPECIFIC_URL_ENCODED_PARAM_TO_DATA_KEY_MAPPING;

        foreach ($urlEncoded as  $paramName => $dataKey) {
            $paramValue = $this->data[$dataKey] ?? null;

            if (false === $this->checkEmpty($paramName, $paramValue)) {
                $paramValue = urlencode($paramValue);
                $requestString .= $paramName . '=' . $paramValue . '&';
            }
        }

        if (!empty($this->requiredParams)) {

            throw new ValidatorException(__(
                sprintf('Required params not provided: %s', implode(',', $this->requiredParams))
            ));
        }

        return rtrim($requestString, '&');
    }

    /**
     * @return void
     */
    protected function defineRequiredParams(): void
    {
        $this->requiredParams = self::COMMON_REQUIRED_PARAMS;
    }

    /**
     * @param string $paramName
     * @param mixed $paramValue
     * @return bool
     * @throws ValidatorException
     */
    private function checkEmpty(string $paramName, mixed $paramValue): bool
    {
        $isEmpty = in_array($paramValue, self::EMPTY_TYPES);

        if (in_array($paramName, $this->requiredParams)) {
            if ($isEmpty) {

                throw new ValidatorException(__($paramName . 'is a required parameter'));
            }

            $this->requiredParams = array_diff($this->requiredParams, [$paramName]);
        }

        return $isEmpty;
    }
}
