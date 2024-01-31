<?php

namespace Lyonscg\SalesPad\Console\Command\CorrectCustomerLinks;

use Lyonscg\SalesPad\Console\Command\CorrectCustomerLinks;
use Lyonscg\SalesPad\Model\ResourceModel\CustomerLink\CollectionFactory;
use Magento\Framework\Exception\ValidatorException;
use RuntimeException;

class Validator
{
    /**
     * @var int
     */
    private $websiteId;
    /**
     * @var bool
     */
    private $isUseStrictComparison;
    /**
     * @var int|null
     */
    private $columnsNumber;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ?int &$websiteId,
        ?bool &$isUseStrictComparison,
        ?int &$columnsNumber,
        CollectionFactory $collectionFactory
    ) {
        $this->websiteId = &$websiteId;
        $this->isUseStrictComparison = &$isUseStrictComparison;
        $this->columnsNumber = &$columnsNumber;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     * @throws ValidatorException
     */
    public function validateBooleanOption(string $name, string $value)
    {
        if (!isset(CorrectCustomerLinks::BOOLEAN_VALUES[$value])) {

            throw new ValidatorException(__(
                sprintf(
                    '"%s" is an invalid value for "%s" option. Allowed values are: %s%s.',
                    $value,
                    $name,
                    PHP_EOL,
                    implode(', ', array_keys(CorrectCustomerLinks::BOOLEAN_VALUES))
                )
            ));
        }
    }

    /**
     * @param string $key
     * @param string $type
     * @return void
     * @throws ValidatorException
     */
    public function validateKey(string $key, string $type = 'simple'): void
    {
        if (!in_array($key, CorrectCustomerLinks::DATA_KEYS[$type])) {

            throw new ValidatorException(__(sprintf('"%s" is an invalid column name.', $key)));
        }
    }

    /**
     * @param array $row
     * @return void
     * @throws ValidatorException
     * @throws RuntimeException
     */
    public function validateColumnsCount(array $row) {
        if (count($row) > $this->columnsNumber) {
            $this->checkIfPropertyIsDefined('columnsNumber');

            throw new ValidatorException(__('The row contains extra columns.'));
        }
    }

    /**
     * @param string $correctTo
     * @return void
     * @throws ValidatorException
     */
    public function validateNonEmptyCorrectToValue(string $correctTo)
    {
        if (empty($correctTo)) {

            throw new ValidatorException(__('"correct_to" value is empty.'));
        }
    }

    /**
     * @return void
     * @throws ValidatorException
     */
    public function validateCompositeElements($structure)
    {
        $count = null;
        foreach ($structure as $element) {
            if (!is_array($element)) {

                continue;
            }

            if ($count === null) {
                $count = count($element);
            }

            if (count($element) !== $count) {

                throw new ValidatorException(__('Data structure issue: some columns lack corresponding columns!'));
            }
        }
    }

    /**
     * @param string $columnName
     * @param array $values
     * @param array $allowedDuplicates
     * @return void
     * @throws ValidatorException
     */
    public function validateValuesUniqueness(string $columnName, array $values, $allowedDuplicates = [])
    {
        $duplicates = array_diff_assoc($values, array_unique($values));
        foreach ($duplicates as $key => $duplicate) {
            if (in_array($duplicate, $allowedDuplicates)) {
                unset($duplicates[$key]);
            }
        }

        if (!empty($duplicates)) {

            throw new ValidatorException(__(
                sprintf(
                    '"%s" column has duplicates: %s',
                    $columnName,
                    implode(', ', $duplicates))
                )
            );
        }
    }

    /**
     * @param array $record
     * @return void
     * @throws ValidatorException
     * @throws RuntimeException
     */
    public function validateAgainstDb(array $record)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('customer_email', $record['customer_email'])
            ->addFieldToFilter('website_id', $this->websiteId);

        if ($collection->getSize() !== count($record['link_id'])) {
            $this->checkIfPropertyIsDefined('websiteId');

            throw new ValidatorException(__('Mismatch with DB in records count.'));
        }

        foreach ($collection as $item) {
            if (!$key = array_search(
                    strval($item->getData('link_id')),
                    $record['link_id'],
                    $this->isUseStrictComparison
                )
            ) {

                throw new ValidatorException(__('Mismatch with DB in link_id.'));
            }

            if ($key !== array_search(
                    strval($item->getData('sales_pad_customer_num')),
                    $record['sales_pad_customer_num'],
                    $this->isUseStrictComparison
                )
            ) {

                throw new ValidatorException(__('Mismatch with DB in link_id, sales_pad_customer_num.'));
            }

            if ($key !== array_search(
                    strval($item->getData('customer_id')),
                    $record['customer_id'],
                    $this->isUseStrictComparison
                )
            ) {

                throw new ValidatorException(__('Mismatch with DB in link_id, sales_pad_customer_num, customer_id.'));
            }

            unset($record['link_id'][$key]);
            unset($record['sales_pad_customer_num'][$key]);
            unset($record['customer_id'][$key]);
        }
    }

    /**
     * @param string $propertyName
     * @return void
     */
    private function checkIfPropertyIsDefined(string $propertyName)
    {
        if ($this->$propertyName === null) {

            throw new RuntimeException(sprintf('"%s" is not yet defined for being used in validation', $propertyName));
        }
    }
}
