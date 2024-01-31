<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Block\Request;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Items extends Template
{
    public const URL_PATH_ITEM_REMOVE = 'orequest/item/remove';

    /**
     * @var string
     */
    protected $_template = 'Capgemini_RequestToOrder::request/info/items.phtml';

    /**
     * @var array[]
     */
    private $requestItems;

    /**
     * @var bool
     */
    private $isQtyEditable = false;

    /**
     * @var bool
     */
    private $canRemove = false;

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * @param UrlInterface $urlInterface
     * @param Context $context
     * @param null|array $data
     */
    public function __construct(
        UrlInterface $urlInterface,
        Context      $context,
        ?array        $data = []
    )
    {
        parent::__construct($context, $data ?: []);
        $this->urlInterface = $urlInterface;
    }

    /**
     * @return bool
     */
    public function isQtyEditable(): bool
    {
        return $this->isQtyEditable;
    }

    /**
     * @param bool $isQtyEditable
     * @return $this
     */
    public function setIsQtyEditable(bool $isQtyEditable): self
    {
        $this->isQtyEditable = $isQtyEditable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCanRemove(): bool
    {
        return $this->canRemove;
    }

    /**
     * @param bool $canRemove
     * @return $this
     */
    public function setCanRemove(bool $canRemove): self
    {
        $this->canRemove = $canRemove;

        return $this;
    }

    /**
     * @return array[]
     */
    public function getRequestItems()
    {
        return $this->requestItems;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setRequestItems(array $items): self
    {
        $this->requestItems = $items;

        return $this;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        if (!$this->getRequestItems()) {
            return __('There are no items');
        }
        return parent::toHtml();
    }

    /**
     * @return string
     */
    public function getRemoveUrl($item)
    {
        return $this->urlInterface->getUrl(
            self::URL_PATH_ITEM_REMOVE,
            [
                'request_id' => $item->getRtoId(),
                'item_id' => $item->getId()
            ]
        );
    }
}
