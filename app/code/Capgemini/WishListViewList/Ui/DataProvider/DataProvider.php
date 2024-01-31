<?php
/**
 * Capgemini_WishListViewList
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WishListViewList
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
declare(strict_types=1);

namespace Capgemini\WishListViewList\Ui\DataProvider;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
as ParentDataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory;

/**
 * Short description
 *
 * @category  Capgemini
 * @package   Capgemini_WishListViewList
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */
class DataProvider extends ParentDataProvider
{
    /**
     * Wishlist collection factory
     *
     * @var CollectionFactory
     */
    protected CollectionFactory $wishlistCollectionFactory;
    /**
     * Short description
     *
     * @var UserContextInterface
     */
    protected UserContextInterface $customerContext;

    /**
     * DataProvider constructor
     *
     * @param string                $name                      Param comment
     * @param string                $primaryFieldName          Param comment
     * @param string                $requestFieldName          Param comment
     * @param Reporting             $reporting                 Param comment
     * @param SearchCriteriaBuilder $searchCriteriaBuilder     Param comment
     * @param RequestInterface      $request                   Param comment
     * @param FilterBuilder         $filterBuilder             Param comment
     * @param UserContextInterface  $customerContext           Param comment
     * @param CollectionFactory     $wishlistCollectionFactory Param comment
     * @param array                 $meta                      Param comment
     * @param array                 $data                      Param comment
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        UserContextInterface $customerContext,
        CollectionFactory $wishlistCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->customerContext = $customerContext;
    }

    /**
     * Short description
     *
     * @return array
     */
    public function getData(): array
    {
        $paging = $this->request->getParam('paging');
        $customerId = $this->customerContext->getUserId();
        $collection = $this->wishlistCollectionFactory->create();

        $collection->filterByCustomerId($customerId);
        if ($paging) {
            if (isset($paging['pageSize'])) {
                $collection->setPageSize($paging['pageSize']);
            }
            if (isset($paging['current'])) {
                $collection->setCurPage($paging['current']);
            }
        }

        $data = $collection->toArray();

        foreach ($data['items'] as &$item) {
            $item['entity_id'] = $item['wishlist_id'];
        }

        return $data;
    }
}
