<?php
/**
 * Capgemini_VcServiceProductPriceMock
 * php version 7.4.27
 *
 * @category  Capgemini
 * @package   Capgemini_VcServiceProductPriceMock
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      https://www.capgemini.com
 */

declare(strict_types=1);

namespace Capgemini\VcServiceProductPriceMock\Plugin\Webapi;

use Magento\Framework\Webapi\Rest\Response;

class ResponsePlugin
{
    /**
     * @param Response $subject
     * @param $outputData
     * @return array
     */
    public function beforePrepareResponse(Response $subject, $outputData): array
    {
        if (isset($outputData['price_data'])) {
            $outputData['data'] = $outputData['price_data'];
            foreach ($outputData['data'] as &$price) {
                if (isset($price['unit_of_measure'])) {
                    $price['unitOfMeasure'] = $price['unit_of_measure'];
                    unset($price['unit_of_measure']);
                }
            }
            unset($outputData['price_data']);
        }

        return [$outputData];
    }
}
