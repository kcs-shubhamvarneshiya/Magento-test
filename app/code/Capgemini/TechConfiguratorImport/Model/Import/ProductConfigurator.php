<?php
/**
 * Capgemini_TechConfiguratorImport
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfiguratorImport\Model\Import;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\StringUtils;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\ImportFactory;
use Magento\ImportExport\Model\ResourceModel\Helper;

/**
 * Product configurator import model
 */
class ProductConfigurator extends \Magento\ImportExport\Model\Import\AbstractEntity
{
    const ENTITY_CODE = 'product_configurator';
    const ERROR_VALUE_IS_REQUIRED = 'valueIsRequired';
    /**
     * @var string
     */
    protected $masterAttributeCode = 'Name';

    /**
     * List of available behaviors
     *
     * @var string[]
     */
    protected $_availableBehaviors = [
        \Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE,
        \Magento\ImportExport\Model\Import::BEHAVIOR_DELETE
    ];

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * Constructor
     *
     * @param StringUtils $string
     * @param ScopeConfigInterface $scopeConfig
     * @param ImportFactory $importFactory
     * @param Helper $resourceHelper
     * @param ResourceConnection $resource
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param Processor $processor
     * @param array $data
     * @param Json|null $serializer
     */
    public function __construct(
        StringUtils $string,
        ScopeConfigInterface $scopeConfig,
        ImportFactory $importFactory,
        Helper $resourceHelper,
        ResourceConnection $resource,
        ProcessingErrorAggregatorInterface $errorAggregator,
        Processor $processor,
        array $data = [],
        Json $serializer = null
    ) {
        parent::__construct($string, $scopeConfig, $importFactory, $resourceHelper, $resource, $errorAggregator, $data, $serializer);
        $this->processor = $processor;
    }

    /**
     * {@inheritDoc}
     */
    protected function _importData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowData) {
                $result = $this->processor->import($rowData, $this->getBehavior($rowData));
                $this->countItemsCreated += $result['created'];
                $this->countItemsDeleted += $result['deleted'];
            }
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityTypeCode()
    {
        return self::ENTITY_CODE;
    }

    /**
     * {@inheritDoc}
     */
    public function validateRow(array $rowData, $rowNumber)
    {
        $this->_processedEntitiesCount++;
        if (!isset($rowData['Name']) || !$rowData['Name']) {
            $this->addRowError(self::ERROR_VALUE_IS_REQUIRED, $rowNumber, 'Name', '\'Name\' field in the \'Product\' is required.');
        };
        return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function validateData()
    {
        if (!$this->_dataValidated) {
            $this->_saveValidatedBunches();
            $this->_dataValidated = true;
        }
        return $this->getErrorAggregator();
    }
}
