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

namespace Capgemini\WishListViewList\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

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
class ProjectActions extends Column
{
    public const URL_PATH_VIEW = 'wishlist/index/index';

    public const URL_PATH_DELETE = 'cpwishlist/index/massdelete';

    /**
     * Short description
     * URL builder
     *
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * Short description
     *
     * @param UrlInterface       $urlBuilder         Param comment
     *                                               Short
     *                                               description
     * @param ContextInterface   $context            Param comment
     *                                               Short
     *                                               description
     * @param UiComponentFactory $uiComponentFactory Param comment
     *                                               Short description
     * @param array              $components         Param comment
     *                                               Short
     *                                               description
     * @param array              $data               Param comment
     */
    public function __construct(
        UrlInterface       $urlBuilder,
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        array              $components = [],
        array              $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Short description
     *
     * @param array $dataSource Param comment
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])
        ) {
            $confirm = [
                'title' => __('Delete project'),
                'message' => __(
                    'Are you sure you want to delete this project?'
                )
            ];

            foreach ($dataSource['data']['items'] as &$item) {
                $itemName = $item['name'];
                if (isset($item['wishlist_id'])) {
                    $item[$this->getData('name')] = [
                        'view' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_VIEW,
                                [
                                    'wishlist_id' => $item['wishlist_id']
                                ]
                            ),
                            'label' => __('View Project')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'wishlist_id' => $item['wishlist_id']
                                ]
                            ),
                            'label' => __('Delete Project'),
                            'confirm' => $confirm
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
