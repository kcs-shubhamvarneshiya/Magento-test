<?php
/**
 * @category    Lyonscg
 * @package     Lyonscg_Contact
 * @copyright   Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 * @author      Logan Montgomery <logan.montgomery@capgemini.com>
 * @see         \Magento\Contact\Model\Mail
 */

namespace Lyonscg\Contact\Model;

use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Mail implements MailInterface
{
    /**
     * Send Copy Enabled config path
     */
    const XML_PATH_SEND_COPY_ENABLED = 'contact/email/send_copy_enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var ConfigInterface
     */
    private $contactsConfig;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;


    /**
     * Initialize dependencies.
     *
     * @param ScopeConfigInterface $config
     * @param ConfigInterface $contactsConfig
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param StoreManagerInterface|null $storeManager
     */
    public function __construct(
        ScopeConfigInterface $config,
        ConfigInterface $contactsConfig,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager = null
    ) {
        $this->config = $config;
        $this->contactsConfig = $contactsConfig;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()->get(StoreManagerInterface::class);
    }

    /**
     * Send email from contact form
     *
     * CLMI-158 - send copy of data to person filling out the contact us form
     *
     * @param string $replyTo
     * @param array $variables
     * @return void
     */
    public function send($replyTo, array $variables)
    {
        /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
        $replyToName = !empty($variables['data']['name']) ? $variables['data']['name'] : null;

        $sendTo = [
            $this->contactsConfig->emailRecipient(),
        ];

        $copySendEnabled = $this->config->isSetFlag(
            self::XML_PATH_SEND_COPY_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
        if ($copySendEnabled && isset($variables['data'])) {
            $sendTo[] = $variables['data']->getEmail();
        }

        foreach ($sendTo as $destinationEmail) {
            if ($destinationEmail === false) {
                continue;
            }
            $this->inlineTranslation->suspend();
            try {
                $transport = $this->transportBuilder
                    ->setTemplateIdentifier($this->contactsConfig->emailTemplate())
                    ->setTemplateOptions(
                        [
                            'area' => Area::AREA_FRONTEND,
                            'store' => $this->storeManager->getStore()->getId()
                        ]
                    )
                    ->setTemplateVars($variables)
                    ->setFrom($this->contactsConfig->emailSender())
                    ->addTo($destinationEmail)
                    ->setReplyTo($replyTo, $replyToName)
                    ->getTransport();

                $transport->sendMessage();
            } finally {
                $this->inlineTranslation->resume();
            }
        }
    }
}
