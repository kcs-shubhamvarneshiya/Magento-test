<?php

namespace Lyonscg\SalesPad\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\LocalizedException;

class ApiErrorLogLifetime extends Value
{
    /**
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave(): ApiErrorLogLifetime
    {
        $value = $this->getValue();

        if ($value !== '' && (!is_numeric($value) || $value <= 0)) {
            $message = __('Invalid Error Log Lifetime: the value must be a number greater than zero or empty.');
        }

        if (isset($message)) {
            throw new \Magento\Framework\Exception\LocalizedException($message);
        }

        return parent::beforeSave();
    }
}
