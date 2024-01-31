<?php

/**
 * Lyonscg_Search
 *
 * @category  Lyons
 * @package   Lyonscg_Search
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Search\Plugin;

use Magento\AdvancedSearch\Model\SuggestedQueriesInterface;
use Magento\Search\Model\Autocomplete\ItemInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Search\Model\AutocompleteInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class AutocompletePlugin
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * AutocompletePlugin constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param AutocompleteInterface $subject
     * @param ItemInterface[] $result
     * @return ItemInterface[]
     */
    public function afterGetItems(
        AutocompleteInterface $subject,
        array $result
    ): array {
        if (!$this->isResultsCountEnabled()) {
            foreach ($result as $item) {
                $item->setNumResults(null);
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isResultsCountEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            SuggestedQueriesInterface::SEARCH_RECOMMENDATIONS_COUNT_RESULTS_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
