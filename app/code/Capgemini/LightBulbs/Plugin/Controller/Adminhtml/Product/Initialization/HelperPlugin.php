<?php

namespace Capgemini\LightBulbs\Plugin\Controller\Adminhtml\Product\Initialization;

use \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper;
use Magento\Framework\App\RequestInterface;

class HelperPlugin
{
    /** @var RequestInterface */
    protected $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @param Helper $subject
     * @param \Magento\Catalog\Model\Product $result
     * @param \Magento\Catalog\Model\Product $ignore
     * @param array $productData
     * @return mixed
     */
    public function afterInitializeFromData(Helper $subject, \Magento\Catalog\Model\Product $result)
    {
        // add qty to upsell links
        $links = $result->getProductLinks();
        // return early if we don't have links to process
        if (!is_array($links)) {
            return $result;
        }

        $linksData = $this->request->getPost('links', []);

        if (empty($linksData)) {
            return $result;
        }
        if (!isset($linksData['upsell']) || !is_array($linksData['upsell'])) {
            return $result;
        }

        $upsellLinks = $linksData['upsell'];

        foreach ($links as $productLink) {
            if ($productLink->getLinkType() === 'upsell') {
                foreach ($upsellLinks as $upsellData) {
                    if (isset($upsellData['sku']) && $upsellData['sku'] === $productLink->getLinkedProductSku()) {
                        $productLink->setQty(intval($upsellData['qty'] ?? 1));
                    }
                }
            }
        }

        return $result;
    }
}
