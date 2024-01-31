<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model\ResourceModel;

use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CategoryLink\CollectionFactory as CategoryLinkCollectionFactory;
use Capgemini\CategoryAds\Model\ResourceModel\CategoryAds\CategoryLinkFactory as CountryLinkResourceFactory;
use Capgemini\CategoryAds\Model\CategoryAds\CategoryLinkFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Exception;

class CategoryAds extends AbstractDb
{
    /**
     * @var CategoryLinkCollectionFactory
     */
    protected $categoryLinkCollectionFactory;

    /**
     * @var CountryLinkResourceFactory
     */
    protected $categoryLinkResourceFactory;

    /**
     * @var CategoryLinkFactory
     */
    protected $categoryLinkFactory;

    /**
     * @param CategoryLinkCollectionFactory $categoryLinkCollectionFactory
     * @param CountryLinkResourceFactory $categoryLinkResourceFactory
     * @param CategoryLinkFactory $categoryLinkFactory
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $connectionName
     */
    public function __construct(
        CategoryLinkCollectionFactory $categoryLinkCollectionFactory,
        CountryLinkResourceFactory $categoryLinkResourceFactory,
        CategoryLinkFactory $categoryLinkFactory,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->categoryLinkCollectionFactory = $categoryLinkCollectionFactory;
        $this->categoryLinkResourceFactory = $categoryLinkResourceFactory;
        $this->categoryLinkFactory = $categoryLinkFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct() : void
    {
        $this->_init(CategoryAdsInterface::PLP_TABLE_NAME, CategoryAdsInterface::ID);
    }

    /**
     * @inheritdoc
     *
     * @param \Capgemini\CategoryAds\Model\CategoryAds $object
     * @throws Exception
     */
    protected function _afterSave(AbstractModel $object): void
    {
        $categories = $object->getCategories();

        if (!is_null($categories)) {

            $countryLinkCollection = $this->categoryLinkCollectionFactory->create();
            $countryLinkCollection->addFieldToFilter('plpad_id', $object->getId());

            $existingCategoriesByCategoryId = [];
            foreach ($countryLinkCollection as $categoryLink) {
                $existingCategoriesByCategoryId[$categoryLink->getCategoryId()] = $categoryLink;
            }

            $existingCategories = array_keys($existingCategoriesByCategoryId);
            $categoriesToDelete = array_diff($existingCategories, $categories);
            $categoriesToAdd = array_diff($categories, $existingCategories);

            if (!empty($categoriesToDelete) || !empty($categoriesToAdd)) {

                $categoryLinkResource = $this->categoryLinkResourceFactory->create();

                if (!empty($categoriesToDelete)) {
                    foreach ($categoriesToDelete as $categoryToDelete) {
                        if (isset($existingCategoriesByCategoryId[$categoryToDelete])) {
                            $categoryLinkResource->delete($existingCategoriesByCategoryId[$categoryToDelete]);
                        }
                    }
                }

                // Add categories associations
                if (!empty($categoriesToAdd)) {
                    foreach ($categoriesToAdd as $categoryToAdd) {
                        $categoryLink = $this->categoryLinkFactory->create();
                        $categoryLink->setData([
                            'plpad_id' => $object->getId(),
                            'category_id' => $categoryToAdd,
                        ]);

                        $categoryLinkResource->save($categoryLink);
                    }
                }
            }
        }
    }
}
