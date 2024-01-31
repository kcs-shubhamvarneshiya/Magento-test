<?php
namespace Capgemini\ImportExport\Plugin;

use Capgemini\ImportExport\Helper\Info;
use \Magento\Framework\Serialize\SerializerInterface;
/**
 * Class parseMultiselectOptionsPlugin
 * @package Capgemini\ImportExport\Plugin
 */
class CsvPlugin
{
    const XML_PATH_IMPORT_MAPPING = 'importexport/general/mapping';
    protected  $helper;

    protected  $serializer;

    public function __construct(
        Info $helper,
        SerializerInterface $serializer
    )
    {
        $this->helper  = $helper;
        $this->serializer = $serializer;
    }
    /**
     * Column names getter.
     *
     * @return array
     */
    public function afterGetColNames($subject, $result)
    {
        if (in_array('stockcode', $result)) {
            $mappingConfig = $this->serializer->unserialize($this->helper->getConfig(self::XML_PATH_IMPORT_MAPPING));
            foreach ($mappingConfig as $map) {

            }
            return $result;

        }
        return $result;
    }
}
