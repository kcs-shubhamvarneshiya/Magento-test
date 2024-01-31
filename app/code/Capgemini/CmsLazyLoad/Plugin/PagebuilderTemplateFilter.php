<?php

namespace Capgemini\CmsLazyLoad\Plugin;

use DOMDocument;
use DOMException;
use Exception;
use Magento\Framework\Math\Random;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\PageBuilder\Model\Filter\Template;
use Psr\Log\LoggerInterface;

/**
 * Page builder Template Filter Class
 */
class PagebuilderTemplateFilter
{
    const IS_LAZYLOAD_ENABLED = 'cms/pagebuilder/capgemini_cmslazyload_enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Random
     */
    private $mathRandom;

    /**
     * @param LoggerInterface $logger
     * @param ConfigInterface $viewConfig
     * @param Random $mathRandom
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        Random $mathRandom
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->mathRandom = $mathRandom;
    }

    /**
     * @param Template $subject
     * @param string $result
     * @return string
     */
    public function beforeFilter(Template $subject, string $result): string
    {
        if ($result === '' || !$this->isEnabled()) {
            return $result;
        }

        $dom = $this->createDomDocument($result);

        $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $image->setAttribute('loading', 'lazy');
        }

        preg_match(
            '/<body>(.+)<\/body><\/html>$/si',
            $dom->saveHTML(),
            $matches
        );

        if (!empty($matches)) {
            if (isset($matches[1])) {
                $result = $matches[1];
            }
        }

        return $result;
    }

    /**
     * Create a DOMDocument from a string
     *
     * @param string $html
     *
     * @return DOMDocument
     */
    private function createDomDocument(string $html) : DOMDocument
    {
        $html = $this->maskScriptTags($html);

        $domDocument = new DOMDocument('1.0', 'UTF-8');
        set_error_handler(
            function ($errorNumber, $errorString) {
                throw new DOMException($errorString, $errorNumber);
            }
        );
        $string = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        try {
            libxml_use_internal_errors(true);
            // LIBXML_SCHEMA_CREATE option added according to this message
            // https://stackoverflow.com/a/66473950/773018
            // Its need to avoid bug described in maskScriptTags()
            // https://bugs.php.net/bug.php?id=52012
            $domDocument->loadHTML(
                '<html><body>' . $string . '</body></html>',
                LIBXML_SCHEMA_CREATE
            );
            libxml_clear_errors();
        } catch (Exception $e) {
            restore_error_handler();
            $this->logger->critical($e);
        }
        restore_error_handler();

        return $domDocument;
    }


    /**
     * Masks "x-magento-template" script tags in html content before loading it into DOM parser
     *
     * DOMDocument::loadHTML() will remove any closing tag inside script tag and will result in broken html template
     *
     * @param string $content
     * @return string
     * @see https://bugs.php.net/bug.php?id=52012
     */
    private function maskScriptTags(string $content): string
    {
        $tag = 'script';
        $content = preg_replace_callback(
            sprintf('#<%1$s[^>]*type="text/x-magento-template\"[^>]*>.*?</%1$s>#is', $tag),
            function ($matches) {
                $key = $this->mathRandom->getRandomString(32, $this->mathRandom::CHARS_LOWERS);
                return '<' . $key . '>' . '</' . $key . '>';
            },
            $content
        );
        return $content;
    }

    /**
     * Returns config setting if page builder enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::IS_LAZYLOAD_ENABLED
        );
    }
}
