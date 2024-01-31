<?php

namespace Lyonscg\CircaLighting\Plugin;

use Magento\Email\Model\Template\Filter;

class EmailTemplatePlugin
{
    public function afterViewDirective(Filter $subject, $result)
    {
        // remove 'pub/' from url, if sent from cron, the static asset base path will be 'pub/static' instead of 'static'
        return preg_replace('/\/pub\/static\/version/', '/static/version', $result, 1);
    }
}
