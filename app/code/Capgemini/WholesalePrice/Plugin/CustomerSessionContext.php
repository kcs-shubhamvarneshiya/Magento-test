<?php
/**
 * Capgemini_WholesalePrice
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_WholesalePrice
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

namespace Capgemini\WholesalePrice\Plugin;

use Capgemini\WholesalePrice\Helper\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * CustomerSessionContext class
 */
class CustomerSessionContext
{
    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var Customer
     */
    protected Customer $customerHelper;

    /**
     * @param Session $customerSession Session
     * @param Customer $customerHelper CustomerHelper
     */
    public function __construct(
        Session $customerSession,
        Customer $customerHelper
    ) {
        $this->customerSession = $customerSession;
        $this->customerHelper = $customerHelper;
    }

    /**
     * @param ActionInterface $subject
     * @param \Closure $proceed
     * @param RequestInterface $request
     *
     * @return mixed
     *
     * @throws NoSuchEntityException
     */
    public function aroundDispatch(
        ActionInterface $subject,
        \Closure $proceed,
        RequestInterface $request
    ) {
        $customerData = $this->customerSession->getCustomer()->getData();

        $this->customerHelper->setCustomerDataToContext($customerData);

        return $proceed($request);
    }
}
