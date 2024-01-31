<?php
/**
 * Capgemini_CategoryAds
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\CategoryAds\Api\Data;

interface CategoryAdsInterface
{
    public const PLP_TABLE_NAME = 'capgemini_plpad';

    /**
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const ID = 'entity_id';
    public const NAME = 'name';
    public const IS_ENABLED = 'enabled';
    public const POSITION = 'position';
    public const CONTENT = 'content';
    public const CATEGORIES = 'categories';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @return string|null
     */
    public function getIsEnabled(): ?string;

    /**
     * @param bool $isEnabled
     * @return void
     */
    public function setIsEnabled(string $isEnabled): void;

    /**
     * @return null|string
     */
    public function getPosition(): ?string;

    /**
     * @param int $position
     * @return void
     */
    public function setPosition(string $position): void;

    /**
     * @return null|string
     */
    public function getContent(): ?string;

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void;

    /**
     * @return array|null
     */
    public function getCategories(): ?array;

    /**
     * @param array $categories
     * @return void
     */
    public function setCategories(array $categories): void;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string|null $createdAt
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Capgemini\CategoryAds\Api\Data\CategoryAdsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Capgemini\CategoryAds\Api\Data\CategoryAdsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Capgemini\CategoryAds\Api\Data\CategoryAdsExtensionInterface $extensionAttributes
    );
}
