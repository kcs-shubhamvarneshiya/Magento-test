<?php
/**
 * Lyonscg_Catalog
 *
 * @category  Lyons
 * @package   Lyonscg_Catalog
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\Catalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Lyonscg\Catalog\Helper
 */
class Config extends AbstractHelper
{
    /**
     * @var string
     */
    const COLLECTION_LIMIT = 'catalog/lyonscg_collection_accessories/collection_limit';

    /**
     * @var string
     */
    const ACCESSORIES_LIMIT = 'catalog/lyonscg_collection_accessories/accessories_limit';


    /**
     * @return mixed
     */
    public function getCollectionLimit()
    {
        return $this->scopeConfig->getValue(self::COLLECTION_LIMIT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getAccessoriesLimit()
    {
        return $this->scopeConfig->getValue(self::ACCESSORIES_LIMIT, ScopeInterface::SCOPE_STORE);
    }

}
