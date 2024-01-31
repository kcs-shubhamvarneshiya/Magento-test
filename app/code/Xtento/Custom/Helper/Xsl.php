<?php

namespace Xtento\Custom\Helper;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Xsl extends \Magento\Framework\App\Helper\AbstractHelper
{
    // IMPORTANT: Remember to add your custom function into allowed functions in etc/xtento/productexport_settings.xml (or if using order export, orderexport_settings.xml)!

    // Static functions which can be called in the XSL Template, example:
    // <xsl:value-of select="php:functionString('Xtento\Custom\Helper\Xsl::sampleFunctionCurrencyByStore', grand_total, store_id)"/>

    // IMPORTANT: Your function MUST be declared "public static" and thus no Dependency Injection is possible. But, you can use the object manager as shown below.

    public static function categoryPaths($pathID, $pathName)
    {
        $pathIDArray = explode("/", $pathID);
        $pathNameArray = explode(' > ', $pathName);
        $output = '[';
        $count = 0;
        foreach ($pathIDArray as $key => $value) {
            $count ++;
            if ($value != '1' && $value != '2') {
                $output .= '{"id":"'.$value.'","name":"'.$pathNameArray[$key].'"}';
                if ($count < count($pathIDArray)) {
                    $output .= ',';
                }
            }
        }
        $output .= ']';
        return $output;
    }
}
