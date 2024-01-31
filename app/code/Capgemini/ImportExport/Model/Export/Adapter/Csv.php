<?php
namespace Capgemini\ImportExport\Model\Export\Adapter;

/**
 * Class Csv
 * @package Capgemini\ImportExport\Model\Export\Adapter
 */
class Csv extends  \Magento\ImportExport\Model\Export\Adapter\Csv
{
    /**
     * Clean cached values
     *
     * @return void
     */
    public function destruct()
    {
        if (is_object($this->_fileHandler)) {
            $this->_fileHandler->close();
            if (!strpos($this->_destination, 'error') > 0) {
                $this->_directoryHandle->delete($this->_destination);
            }
        }
    }
}
