<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class AddToRequest implements ArgumentInterface
{
    public const URI_REQUEST_SUBMIT = 'orequest/item/add';

    /**
     * @return string
     */
    public function getSubmitUri(): string
    {
        return self::URI_REQUEST_SUBMIT;
    }
}
