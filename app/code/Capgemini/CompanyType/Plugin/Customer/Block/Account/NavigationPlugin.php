<?php
/**
 * Capgemini_CompanyType
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\CompanyType\Plugin\Customer\Block\Account;

use Magento\Customer\Block\Account\Navigation;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Plugin for the account navigation links.
 */
class NavigationPlugin
{
    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $localeResolver;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\Locale\Resolver $localeResolver
    ) {
        $this->localeResolver = $localeResolver;
    }

    /**
     * Remove "My wallet" and "Stored Payment Methods" links for the non-US sites
     *
     * @param Navigation $subject
     * @param $result
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterSetLayout(Navigation $subject, $result)
    {
        $locale = $this->localeResolver->getLocale();
        if (substr($locale, -2) != 'US') {
            $subject->getLayout()->unsetElement('customer-account-navigation-my-credit-cards-link');
            $subject->getLayout()->unsetElement('customer-account-navigation-myblog');
        }
        return $result;
    }
}
