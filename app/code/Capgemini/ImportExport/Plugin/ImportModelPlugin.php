<?php
namespace Capgemini\ImportExport\Plugin;

use Capgemini\ImportExport\Helper\Info;

/**
 * Class ImportModelPlugin
 * @author Yaroslav Protsko <yaroslav.protsko@capgemini.com>
 * @copyright 2021 Capgemini, Inc. (www.capgemini.com)
 */
class ImportModelPlugin
{
    /**
     * @var Info
     */
    protected  $helper;

    /**
     * @var SerializerInterface
     */
    protected  $serializer;

    /**
     * ImportModelPlugin constructor.
     * @param Info $helper
     */
    public function __construct(
        Info $helper
    )
    {
        $this->helper  = $helper;
    }

    /**
     * @param $subject
     * @param $entity
     * @return mixed
     */
    public function afterGetEntity($subject, $entity)
    {
        if ($entity == $this->helper::CUSTOM_IMPORT_PRODUCT_ENTITY_CODE) {
            return $this->helper::DEFAULT_IMPORT_PRODUCT_ENTITY_CODE;
        }

        return $entity;
    }
}
