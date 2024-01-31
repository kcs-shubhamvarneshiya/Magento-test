<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\Block;

use Capgemini\LightBulbs\Helper\Data;
use Magento\Backend\Block\Template\Context;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\Cms\Api\BlockRepositoryInterface;

/**
 * Class Details
 * @package Lyonscg\RequisitionList\Block
 */
class Details extends \Magento\RequisitionList\Block\Requisition\View\Details
{
    const BULBS_STATE_HAS = 1;
    const BULBS_STATE_HAS_NO_BUT_CAN_HAVE = 2;
    const BULBS_STATE_HAS_NO_AND_CAN_NOT_HAVE = 3;
    const QUOTE_MODAL_SAVE_ERROR_CMS_BLOCK = 'quote_modal_save_error';
    const DEFAULT_MODAL_ERROR_MESSAGE_CONTENT = '<h2>Something went wrong. Please contact the administrator.</h2>';

    /**
     * @var Data
     */
    private $bulbsHelper;
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    public function __construct(
        Context $context,
        RequisitionListRepositoryInterface $requisitionListRepository,
        Data $bulbsHelper,
        BlockRepositoryInterface $blockRepository,
        array $data = []
    ) {
        parent::__construct($context, $requisitionListRepository, $data);
        $this->bulbsHelper = $bulbsHelper;
        $this->blockRepository = $blockRepository;
    }

    /**
     * @param int $bulbsState
     * @return string
     */
    public static function getToggleBulbsButtonInscription($bulbsState)
    {
        switch ($bulbsState) {
            case self::BULBS_STATE_HAS:
                return 'Remove Bulb(s) from Quote';
            case self::BULBS_STATE_HAS_NO_BUT_CAN_HAVE:
                return 'Add Bulb(s) to Quote';
            default:
                return '';
        }
    }

    /**
     * Get url for downloading pdf
     *
     * @return string
     * @SuppressWarnings(PHPMD.RequestAwareBlockMethod)
     */
    public function getPdfUrl()
    {
        return $this->getUrl('*/*/pdf');
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getBulbsState()
    {
        $listItems = $this->getRequisitionList()->getItems();
        $hasBulb = $this->bulbsHelper->isListHasBulb($listItems,RequisitionListInterface::class);

        if ($hasBulb) {
            return self::BULBS_STATE_HAS;
        } else {
            if ($this->bulbsHelper->canListHaveBulb($listItems,RequisitionListInterface::class)) {
                return self::BULBS_STATE_HAS_NO_BUT_CAN_HAVE;
            } else {
                return self::BULBS_STATE_HAS_NO_AND_CAN_NOT_HAVE;
            }
        }
    }

    /**
     * @return string
     */
    public function getCartUrl()
    {
        return $this->getUrl('circalighting/docupdate/cart');
    }

    /**
     * @return string
     */
    public function getQuoteUrl()
    {
        return $this->getUrl('circalighting/docupdate/quote');
    }

    /**
     * @return string
     */
    public function getSaveErrorModalContent()
    {
        try {

            $content =  $this->blockRepository
                ->getById(self::QUOTE_MODAL_SAVE_ERROR_CMS_BLOCK)
                ->getContent();
        } catch (\Exception $exception) {

            return self::DEFAULT_MODAL_ERROR_MESSAGE_CONTENT;
        }

        return $content ?: self::DEFAULT_MODAL_ERROR_MESSAGE_CONTENT;
    }
}
