<?php
/**
 * Capgemini_TechConfigurator
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfigurator\Model\ResourceModel;

use Capgemini\TechConfigurator\Model\ResourceModel\Config\Exception;
use Capgemini\TechConfigurator\Model\ResourceModel\Config\Footnote;
use Capgemini\TechConfigurator\Model\ResourceModel\Config\SkuPart;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Configurator resource model
 */
class Config extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var Exception
     */
    protected $exceptionResource;
    /**
     * @var Footnote
     */
    protected Footnote $footnoteResource;
    /**
     * @var SkuPart
     */
    protected SkuPart $skuPartResource;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Exception $exceptionResource
     * @param Footnote $footnoteResource
     * @param SkuPart $skuPartResource
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        Exception $exceptionResource,
        Footnote $footnoteResource,
        SkuPart $skuPartResource,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->exceptionResource = $exceptionResource;
        $this->footnoteResource = $footnoteResource;
        $this->skuPartResource = $skuPartResource;
        $this->connectionName = $connectionName;
    }

    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('vc_configurator_config', 'entity_id');
    }

    /**
     * {@inheritDoc}
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var \Capgemini\TechConfigurator\Api\Data\ConfigInterface $object */
        parent::_afterSave($object);
        $configId = $object->getId();
        if ($object->getFootnotes()) {
            foreach ($object->getFootnotes() as $footnote) {
                $footnote->setConfigId($configId);
                $this->footnoteResource->save($footnote);
            }
        }
        if ($object->getSkuParts()) {
            foreach ($object->getSkuParts() as $skuPart) {
                $skuPart->setConfigId($configId);
                $this->skuPartResource->save($skuPart);
            }
        }
        if ($object->getExceptions()) {
            foreach ($object->getExceptions() as $exception) {
                $exception->setConfigId($configId);
                $this->exceptionResource->save($exception);
            }
        }
        return $this;
    }
}
