<?php
/**
 * Capgemini_TechConfiguratorImport
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\TechConfiguratorImport\Model\Import\Source;

/**
 * JSON file source for product configurator import
 */
class Json extends \Magento\ImportExport\Model\Import\AbstractSource
{
    /**
     * Data
     *
     * @var array
     */
    protected $items;

    /**
     * Constructor
     * @param string $file
     */
    public function __construct(
        $file
    ) {
        $json = file_get_contents($file);
        $data = json_decode($json, true);
        $columns = [];
        $this->items = [];
        if (isset($data['Products'])) {
            foreach ($data['Products'] as $product) {
                if (isset($product['Product'])) {
                    $item = $product['Product'];
                    $this->items[] = $item;
                    foreach (array_keys($item) as $column) {
                        if (!in_array($column, $columns)) {
                            $columns[] = $column;
                        }
                    }
                }
            };
        }
        parent::__construct($columns);
    }

    /**
     * Render next row
     *
     * Return array or false on error
     *
     * @return array|false
     */
    protected function _getNextRow()
    {
        $key = $this->key();
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }
        return false;
    }
}