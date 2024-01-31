<?php
/**
 * Capgemini_WholesaleAddress
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\WholesaleAddress\Plugin;

use Magento\Customer\Controller\Address;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Plugin to restrict address editing actions for wholesale customers
 */
class AddressControllerPlugin
{
    /**
     * @var \Capgemini\WholesaleAddress\ViewModel\WholesaleDetector
     */
    protected $wholesaleDetector;

    /**
     * @param \Capgemini\WholesaleAddress\ViewModel\WholesaleDetector $wholesaleDetector
     */
    public function __construct(\Capgemini\WholesaleAddress\ViewModel\WholesaleDetector $wholesaleDetector)
    {
        $this->wholesaleDetector = $wholesaleDetector;
    }

    /**
     * Restrict address editing for wholesale customers
     *
     * @param Address $subject
     * @param RequestInterface $request
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function aroundExecute(Address $subject, callable $proceed)
    {
        $actionName = $subject->getRequest()->getActionName();
        if ($actionName !== 'index' &&
            $this->wholesaleDetector->isWholesaleCustomer()) {
            throw new NotFoundException(__('This action is not allowed for wholesale customers.'));
        } else {
            return $proceed();
        }
    }
}
