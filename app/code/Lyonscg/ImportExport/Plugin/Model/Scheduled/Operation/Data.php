<?php
/**
 * Copyright © 2016 Lyons Consulting Group, LLC. All rights reserved.
 */

namespace Lyonscg\ImportExport\Plugin\Model\Scheduled\Operation;

/**
 * Class Data
 */
class Data
{
    /**
     * Add CRON option to scheduled import / export frequency options
     *
     * @param \Magento\ScheduledImportExport\Model\Scheduled\Operation\Data $data
     * @param $options
     * @return Array
     */
    public function afterGetFrequencyOptionArray(\Magento\ScheduledImportExport\Model\Scheduled\Operation\Data $data, $options)
    {
        $options['C'] = 'CRON';
        return $options;
    }
}
