<?php

namespace Capgemini\GoogleTagManager\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\ValidatorException;

class Url extends Value
{
    /**
     * @throws ValidatorException
     */
    public function beforeSave()
    {
        $url = $this->getValue();
        if (!preg_match(
            '#^http(s)?://[A-Za-z\d]+([A-Za-z\d\-.]?[A-Za-z\d]+)*\.[A-Za-z]+/$#',
            $url
        )) {

            throw new ValidatorException(__(
                sprintf(
                    'Please enter a valid URL. Protocol is required (http://, https://). Your value was %s.',
                    $url
                ))
            );
        }

        parent::beforeSave();
    }
}
