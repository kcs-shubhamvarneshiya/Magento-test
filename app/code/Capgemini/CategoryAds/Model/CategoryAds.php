<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Model;

use Capgemini\CategoryAds\Api\Data\CategoryAdsExtensionInterface;
use Capgemini\CategoryAds\Api\Data\CategoryAdsInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class CategoryAds extends AbstractExtensibleModel implements CategoryAdsInterface
{

    public const FIRST_POSITION = 1;

    public const SECOND_POSITION = 2;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\CategoryAds::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsEnabled(): ?string
    {
        return $this->getData(self::IS_ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsEnabled(string $isEnabled): void
    {
        $this->setData(self::IS_ENABLED, $isEnabled);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): string
    {
        return $this->getData(self::POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(string $position): void
    {
        $this->setData(self::POSITION, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent(string $content): void
    {
        $this->setData(self::CONTENT, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(): ?array
    {
        return $this->getData(self::CATEGORIES);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategories(array $categories): void
    {
        $this->setData(self::CATEGORIES, $categories);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
         $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * {@inheritdoc}
     *
     * @return CategoryAdsExtensionInterface|null
     */
    public function getExtensionAttributes(): ?CategoryAdsExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param CategoryAdsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(CategoryAdsExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
