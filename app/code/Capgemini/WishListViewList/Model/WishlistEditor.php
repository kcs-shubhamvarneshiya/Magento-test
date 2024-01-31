<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Capgemini\WishListViewList\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\MultipleWishlist\Helper\Data;
use Magento\Wishlist\Model\ResourceModel\Wishlist\Collection;
use Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory;
use Magento\Wishlist\Model\Wishlist;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Managing a multiple wishlist
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class WishlistEditor extends \Magento\MultipleWishlist\Model\WishlistEditor
{
    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var CollectionFactory
     */
    protected $wishlistColFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param WishlistFactory $wishlistFactory
     * @param Session $customerSession
     * @param CollectionFactory $wishlistColFactory
     * @param Data $helper
     */
    public function __construct(
        WishlistFactory $wishlistFactory,
        Session $customerSession,
        CollectionFactory $wishlistColFactory,
        Data $helper
    ) {
        $this->wishlistFactory = $wishlistFactory;
        $this->customerSession = $customerSession;
        $this->wishlistColFactory = $wishlistColFactory;
        $this->helper = $helper;
        parent::__construct($wishlistFactory, $customerSession, $wishlistColFactory, $helper);
    }

    /**
     * Edit wishlist
     *
     * @param int $customerId
     * @param string $wishlistName
     * @param bool $visibility
     * @param int $wishlistId
     *
     * @return Wishlist
     *
     * @throws LocalizedException
     */
    public function edit($customerId, $wishlistName, $visibility = false, $wishlistId = null)
    {
        if (!$customerId) {
            throw new LocalizedException(__('Sign in to edit projects.'));
        }

        /** @var Wishlist $wishlist */
        $wishlist = $this->wishlistFactory->create();

        if ($wishlistId) {
            $wishlist->load($wishlistId);
            if (((int) $wishlist->getCustomerId()) !== ((int) $this->customerSession->getCustomerId())) {
                throw new LocalizedException(
                    __('The project is not assigned to your account and can\'t be edited.')
                );
            }
        } else {
            if (empty($wishlistName)) {
                throw new LocalizedException(__('Provide the project name.'));
            }

            /** @var Collection $wishlistCollection */
            $wishlistCollection = $this->wishlistColFactory->create();
            $wishlistCollection->filterByCustomerId($customerId);

            $limit = $this->helper->getWishlistLimit();
            if ($this->helper->isWishlistLimitReached($wishlistCollection)) {
                throw new LocalizedException(
                    __('Only %1 project(s) can be created.', $limit)
                );
            }

            $wishlistCollection->addFieldToFilter('name', $wishlistName);

            if ($wishlistCollection->getSize()) {
                throw new LocalizedException(
                    __('Project "%1" already exists.', $wishlistName)
                );
            }

            $wishlist->setCustomerId($customerId);
            $wishlist->generateSharingCode();
        }

        $wishlist->setName($wishlistName)
            ->setVisibility($visibility)
            ->save();

        return $wishlist;
    }
}
