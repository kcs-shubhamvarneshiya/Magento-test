<?php


namespace Capgemini\CustomerDataPurger\Model\Config;


class Converter implements \Magento\Framework\Config\ConverterInterface
{
    public function convert($source): array
    {
        $tables = $source->getElementsByTagName('table');
            $deleteRecord = [];
            $turnToDefault = [];
            $replaceWithPlaceholder = [];

        foreach ($tables as $table) {
            $tableName = $table->getAttribute('name');
            $columns = $table->getElementsByTagName('column');

            if (!$columns->length) {
                $deleteRecord[$tableName]['referential_path'] = $table->getAttribute('referential_path');
                $deleteRecord[$tableName]['identity_column'] = $table->getAttribute('identity_column');

                continue;
            }

            foreach ($columns as $column) {
                $columnName = $column->getAttribute('name');

                $placeholder = $column->getElementsByTagName('placeholder')->item(0);

                if (!$placeholder) {
                    if (empty($turnToDefault[$tableName]['referential_path'])) {
                        $turnToDefault[$tableName]['referential_path'] = $table->getAttribute('referential_path');
                        $turnToDefault[$tableName]['identity_column'] = $table->getAttribute('identity_column');
                    }
                    $turnToDefault[$tableName]['columns'][] = $columnName;
                } else {
                    if (empty($replaceWithPlaceholder[$tableName]['referential_path'])) {
                        $replaceWithPlaceholder[$tableName]['identity_column'] = $table->getAttribute('identity_column');
                        $replaceWithPlaceholder[$tableName]['referential_path'] = $table->getAttribute('referential_path');
                    }
                    $replaceWithPlaceholder[$tableName]['columns'][$columnName] = $placeholder->nodeValue;
                }
            }
        }

        return ['purge_instructions' => compact('deleteRecord', 'turnToDefault', 'replaceWithPlaceholder')];
    }
}
