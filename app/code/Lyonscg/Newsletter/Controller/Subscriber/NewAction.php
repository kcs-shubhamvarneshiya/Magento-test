<?php
/**
 * Lyonscg_Newsletter
 *
 * @category  Lyons
 * @package   Lyonscg_Newsletter
 * @author    Tanya Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\Newsletter\Controller\Subscriber;

use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Validator\EmailAddress as EmailValidator;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;

class NewAction //extends \Magento\Newsletter\Controller\Subscriber\NewAction
{
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param SubscriberFactory $subscriberFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param CustomerUrl $customerUrl
     * @param CustomerAccountManagement $customerAccountManagement
     * @param EmailValidator|null $emailValidator
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     */
    public function __construct(
//        Context $context,
//        SubscriberFactory $subscriberFactory,
//        Session $customerSession,
//        StoreManagerInterface $storeManager,
//        CustomerUrl $customerUrl,
//        CustomerAccountManagement $customerAccountManagement,
//        EmailValidator $emailValidator,
//        CookieManagerInterface $cookieManager,
//        CookieMetadataFactory $cookieMetadataFactory
    ) {
//        parent::__construct(
//            $context,
//            $subscriberFactory,
//            $customerSession,
//            $storeManager,
//            $customerUrl,
//            $customerAccountManagement,
//            $emailValidator
//        );
//        $this->cookieManager = $cookieManager;
//        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * New subscription action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
//    public function execute(): \Magento\Framework\Controller\Result\Redirect
//    {
//        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
//            $email = (string)$this->getRequest()->getPost('email');
//
//            try {
//                $this->validateEmailFormat($email);
//                $this->validateGuestSubscription();
//                $this->validateEmailAvailable($email);
//
//                $subscriber = $this->_subscriberFactory->create()->loadByEmail($email);
//                if ($subscriber->getId()
//                    && (int) $subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED
//                ) {
//                    $this->setNewsletterResponseCookie(__('This email address is already subscribed.'), 'already_subscribed');
//                    throw new LocalizedException(
//                        __('This email address is already subscribed.')
//                    );
//                }
//
//                $status = (int) $this->_subscriberFactory->create()->subscribe($email);
//                $this->messageManager->addSuccessMessage($this->_getSuccessMessage($status));
//                $this->setNewsletterResponseCookie($this->_getSuccessMessage($status), 'success');
//            } catch (LocalizedException $e) {
//                $this->messageManager->addErrorMessage($e->getMessage());
//            } catch (\Exception $e) {
//                $this->setNewsletterResponseCookie(__('Something went wrong with the subscription.'), 'error');
//                $this->messageManager->addExceptionMessage($e, __('Something went wrong with the subscription.'));
//            }
//        }
//        /** @var \Magento\Framework\Controller\Result\Redirect $redirect */
//        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
//        $redirectUrl = $this->_redirect->getRedirectUrl();
//        return $redirect->setUrl($redirectUrl);
//    }

    /**
     * Get success message
     *
     * @param int $status
     * @return Phrase
     */
//    private function _getSuccessMessage(int $status): Phrase
//    {
//        if ($status === Subscriber::STATUS_NOT_ACTIVE) {
//            return __('The confirmation request has been sent.');
//        }
//
//        return __('Thank you for your subscription.');
//    }

    /**
     * @param $response
     * @param $responseType
     */
//    private function setNewsletterResponseCookie($response, $responseType)
//    {
//        try {
//            $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata()
//                ->setDuration(120)
//                ->setPath('/')
//                ->setHttpOnly(false);
//
//            $this->cookieManager->setPublicCookie(
//                'newsletter_response',
//                $response,
//                $publicCookieMetadata
//            );
//
//            $this->cookieManager->setPublicCookie(
//                'newsletter_response_type',
//                $responseType,
//                $publicCookieMetadata
//            );
//        } catch (\Exception $e) {
//
//        }
//    }
}
