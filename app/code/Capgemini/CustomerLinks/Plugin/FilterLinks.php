<?php

namespace Capgemini\CustomerLinks\Plugin;

use Capgemini\CustomerLinks\Model\Config as LinkConfig;

class FilterLinks
{
    protected $linkConfig;

    public function __construct(
        LinkConfig $linkConfig
    )
    {
        $this->linkConfig = $linkConfig;
    }

    /**
     * @param \Magento\Customer\Block\Account\Navigation $subject
     * @param $result
     * @return mixed
     */
    public function afterGetLinks(\Magento\Customer\Block\Account\Navigation $subject, $result)
    {
        foreach ($result as $index => $link) {
            /* @var \Magento\Customer\Block\Account\Link $link */
            $url = $link->getHref();

            if (null === $url) {
                continue;
            }

            $excludeLinks = $this->linkConfig->getExcludeLinks();
            foreach ($excludeLinks as $link) {
                if (empty($link)) {
                    continue;
                }
                if (strpos(strtolower($url), strtolower($link)) !== false) {
                    unset($result[$index]);
                }
            }
        }

        return $result;
    }
}
