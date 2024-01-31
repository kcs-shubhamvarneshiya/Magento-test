<?php

namespace Capgemini\AmastyCustomFormFix\Model\Template;

use Amasty\Customform\Model\Mail\MessageBuilder;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\TransportInterface;

class TransportBuilder extends \Amasty\Customform\Model\Template\TransportBuilder
{
    private static $isAmastyAddingAttachment = false;
    /**
     * @var array
     */
    private $parts = [];

    /**
     * Setter/Getter for self::isAmastyAddingAttachment
     *
     * @param bool|null $bool
     * @return bool|void
     */
    public static function isAmastyAddingAttachment(bool $bool = null)
    {
        if (null === $bool) {

            return self::$isAmastyAddingAttachment;
        }

        self::$isAmastyAddingAttachment = $bool;
    }

    /**
     * Get mail transport
     *
     * @return TransportInterface
     * @throws LocalizedException
     */
    public function getTransport(): TransportInterface
    {
        if (!self::$isAmastyAddingAttachment) {

            return parent::getTransport();
        }

        try {
            $this->prepareMessage();
            $mailTransport = $this->objectManager->create(
                \Capgemini\AmastyCustomFormFix\Model\Transport::class,
                ['message' => clone $this->message]
            );
        } finally {
            $this->reset();
        }

        return $mailTransport;
    }

    /**
     * @param $body
     * @param null $filename
     * @param string $mimeType
     * @param string $disposition
     * @param string $encoding
     *
     * @return \Amasty\Customform\Model\Template\TransportBuilder
     */
    public function addAttachment(
        $body,
        $filename = null,
        $mimeType = Mime::TYPE_OCTETSTREAM,
        $disposition = Mime::DISPOSITION_ATTACHMENT,
        $encoding = Mime::ENCODING_BASE64
    ) {
        if ($this->message && method_exists($this->message, 'createAttachment')) {
            $this->message->createAttachment(
                $body,
                $mimeType,
                $disposition,
                $encoding,
                $filename
            );
        } else {
            $mp = new MimePart($body);
            $mp->encoding = $encoding;
            $mp->type = $mimeType;
            $mp->disposition = $disposition;
            $mp->filename = $filename;
            $this->parts[] = $mp;
        }

        self::$isAmastyAddingAttachment = true;

        return $this;
    }

    /**
     * @return \Amasty\Customform\Model\Template\TransportBuilder
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function prepareMessage()
    {
        parent::prepareMessage();

        /**
         * @var MessageBuilder $messageBuilder
         */
        $messageBuilder = $this->messageBuilderFactory->create();
        $this->message = $messageBuilder
            ->setOldMessage($this->message)
            ->setMessageParts($this->parts)
            ->build();

        return $this;
    }

}
