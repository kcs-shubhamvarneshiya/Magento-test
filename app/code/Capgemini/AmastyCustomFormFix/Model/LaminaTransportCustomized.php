<?php

namespace Capgemini\AmastyCustomFormFix\Model;

use Capgemini\AmastyCustomFormFix\Model\Template\TransportBuilder;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Sendmail;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

class LaminaTransportCustomized extends Sendmail
{
    public function mailHandler($to, $subject, $message, $headers, $parameters)
    {
        $to      = str_replace("\r\n", "\n", $to);
        $subject = str_replace("\r\n", "\n", $subject);
        $message    = str_replace("\r\n", "\n", $message);
        $headers = str_replace("\r\n", "\n", $headers);

        TransportBuilder::isAmastyAddingAttachment(false);

        parent::mailHandler($to, $subject, $message, $headers, $parameters);
    }
}
