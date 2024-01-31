<?php
namespace Capgemini\AvaTax\Plugin\Helper\Rest;

/**
 * Config Plugin
 */
class ConfigPlugin
{
    /**
     * Fix API issue. Return "Mixed" instead of "1".
     *
     * @param \ClassyLlama\AvaTax\Helper\Rest\Config $subject
     * @param $result
     * @return string
     */
    public function afterGetTextCaseMixed(\ClassyLlama\AvaTax\Helper\Rest\Config $subject, $result)
    {
        return "Mixed";
    }
}
