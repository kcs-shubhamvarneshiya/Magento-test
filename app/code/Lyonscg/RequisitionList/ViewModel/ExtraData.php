<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\ViewModel;

use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Stdlib\DateTime;

/**
 * Class ExtraData
 * @package Lyonscg\RequisitionList\ViewModel
 */
class ExtraData extends DataObject implements ArgumentInterface
{

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var DateTime
     */
    protected $dateTime;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Venue constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param StoreManagerInterface $storeManager
     * @param DateTime $dateTime
     * @param array $data
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        StoreManagerInterface $storeManager,
        DateTime $dateTime,
        array $data = []
    ) {
        parent::__construct($data);
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
    }

    /**
     * @return CustomerRepositoryInterface
     */
    public function getCustomer()
    {
        return $this->customerRepository;
    }

    /**
     * @param $date
     * @return string|null
     */
    public function formatDate($date)
    {
        return $this->dateTime->formatDate($date, false);
    }

    /**
     * @return string
     */
    public function getMediaUrl()
    {
        try {
            return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } catch (\Exception $e) {
            return '';
        }
    }
}
